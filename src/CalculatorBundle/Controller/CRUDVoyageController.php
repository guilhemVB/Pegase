<?php

namespace CalculatorBundle\Controller;

use CalculatorBundle\Entity\Voyage;
use AppBundle\Repository\DestinationRepository;
use CalculatorBundle\Repository\StageRepository;
use CalculatorBundle\Service\CRUD\CRUDVoyage;
use CalculatorBundle\Service\Stats\VoyageStats;
use CalculatorBundle\Service\VoyageService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/crud/voyage")
 */
class CRUDVoyageController extends Controller
{

    /**
     * @Route("/create", name="voyageCRUDCreate")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $destinationRepository DestinationRepository */
        $destinationRepository = $em->getRepository('AppBundle:Destination');

        $name = $request->get('name');
        $deparatureDate = $request->get('deparatureDate');
        $destinationId = $request->get('destinationId');
        $destination = $destinationRepository->find($destinationId);

        /** @var CRUDVoyage $CRUDVoyage */
        $CRUDVoyage = $this->get('crud_voyage');
        $voyage = $CRUDVoyage->add($this->getUser(), $name, $deparatureDate, $destination);

        return new JsonResponse(['nextUri' => $this->generateUrl('voyage', ['token' => $voyage->getToken()])]);
    }


    /**
     * @Route("{id}/update", name="voyageCRUDUpdate")
     * @param Voyage $voyage
     * @param Request $request
     * @return Response
     */
    public function updateAction(Voyage $voyage, Request $request)
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $destinationRepository DestinationRepository */
        $destinationRepository = $em->getRepository('AppBundle:Destination');

        $name = $request->get('name');
        $deparatureDate = $request->get('deparatureDate');
        $destinationId = $request->get('destinationId');
        $destination = $destinationRepository->find($destinationId);

        /** @var CRUDVoyage $CRUDVoyage */
        $CRUDVoyage = $this->get('crud_voyage');
        $voyage = $CRUDVoyage->update($voyage, $name, $deparatureDate, $destination);

        return new JsonResponse(['nextUri' => $this->generateUrl('voyage', ['token' => $voyage->getToken()])]);
    }


    /**
     * @Route("{id}/updateShowPricesInPublic", name="voyageCRUDUpdateShowPricesInPublic")
     * @param Voyage $voyage
     * @param Request $request
     * @return Response
     */
    public function updateShowPricesAction(Voyage $voyage, Request $request)
    {
        $showPricesInPublic = $request->get('showPricesInPublic') === 'true';

        $voyage->setShowPricesInPublic($showPricesInPublic);

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();
        $em->persist($voyage);
        $em->flush();

        return new JsonResponse([
            'voyageId' => $voyage->getId(),
            'showPricesInPublic' => $showPricesInPublic,
        ]);
    }

    /**
     * @Route("/{id}/change-transport-type", name="changeTransportTypeVoyage")
     * @param Voyage $voyage
     * @param Request $request
     * @return JsonResponse
     */
    public function changeTransportTypeVoyageAction(Voyage $voyage, Request $request)
    {
        $transportType = $request->get('transportType');

        /** @var CRUDVoyage $CRUDVoyage */
        $CRUDVoyage = $this->get('crud_voyage');
        $CRUDVoyage->changeTransportType($voyage, $transportType);

        /** @var VoyageService $voyageService */
        $voyageService = $this->get('voyage_service');
        $maplaceData = $voyageService->buildMaplaceDataFromVoyage($voyage);

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('CalculatorBundle:Stage');

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);
        $voyageStatsCalculated = $voyageStats->calculateAllStats($voyage, $stagesSorted);

        return new JsonResponse([
            'maplaceData'         => $maplaceData,
            'statsView'           => $this->renderView('CalculatorBundle:Voyage:dashboardStats.html.twig', ['voyageStats' => $voyageStatsCalculated]),
            'destinationListView' => $this->renderView('CalculatorBundle:Voyage:dashboardDestinationsList.html.twig',
                ['stagesSorted' => $stagesSorted, 'voyage' => $voyage, 'voyageStats' => $voyageStatsCalculated]),
        ]);
    }

}
