<?php

namespace CalculatorBundle\Controller;

use CalculatorBundle\Entity\Voyage;
use AppBundle\Repository\DestinationRepository;
use CalculatorBundle\Service\CRUD\CRUDVoyage;
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
        $CRUDVoyage->add($this->getUser(), $name, $deparatureDate, $destination);

        return new JsonResponse(['nextUri' => $this->generateUrl('dashboard')]);
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
        $CRUDVoyage->update($voyage, $name, $deparatureDate, $destination);

        return new JsonResponse(['nextUri' => $this->generateUrl('dashboard')]);
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

}
