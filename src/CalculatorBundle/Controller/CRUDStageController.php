<?php

namespace CalculatorBundle\Controller;

use AppBundle\Entity\Destination;
use CalculatorBundle\Entity\Stage;
use AppBundle\Entity\User;
use CalculatorBundle\Repository\StageRepository;
use CalculatorBundle\Service\CRUD\CRUDStage;
use CalculatorBundle\Service\Stats\VoyageStats;
use CalculatorBundle\Service\VoyageService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/crud/stage")
 */
class CRUDStageController extends Controller
{

    /**
     * @Route("/create/destination/{id}/add", name="addStage")
     * @param Destination $destination
     * @param Request $request
     * @return JsonResponse
     */
    public function addStageAction(Destination $destination, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $voyages = $user->getVoyages();
        if (count($voyages) === 0) {
            $error = "Can't find voyage";
            return new JsonResponse(['error' => $error, 'nextUri' => $this->generateUrl('dashboard')], 400);
        }

        $nbDays = $request->get('nbDays');
        if ($nbDays == 0) {
            $error = "nbDays cannot be empty";
            return new JsonResponse(['error' => $error, 'nextUri' => $this->generateUrl('dashboard')], 400);
        }

        /** @var CRUDStage $CRUDStage */
        $CRUDStage = $this->get('crud_stage');
        $CRUDStage->add($destination, $voyages[0], $nbDays);

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('CalculatorBundle:Stage');

        $stages = $stageRepository->findStagesFromDestinationAndVoyage($destination, $voyages[0]);

        $response = ['success' => true];

        if ($request->get('addBtnAddToVoyage')) {
            $response['btnAddToVoyage'] = $this->renderView('AppBundle:Destination:addAndRemoveDestinationBtn.html.twig',
                [
                    'user'        => $this->getUser(),
                    'destination' => $destination,
                    'stages'      => $stages,
                ]);
        }

        return new JsonResponse($response);
    }


    /**
     * @Route("/{id}/remove", name="removeStage")
     * @param Stage $stage
     * @param Request $request
     * @return JsonResponse
     */
    public function removeStageAction(Stage $stage, Request $request)
    {
        /** @var CRUDStage $CRUDStage */
        $CRUDStage = $this->get('crud_stage');
        $CRUDStage->remove($stage);

        $destination = $stage->getDestination();

        $response = ['success' => true];

        if ($request->get('addBtnAddToVoyage')) {
            /** @var $em EntityManager $em */
            $em = $this->get('doctrine')->getManager();

            /** @var $stageRepository StageRepository */
            $stageRepository = $em->getRepository('CalculatorBundle:Stage');

            $stages = $stageRepository->findStagesFromDestinationAndVoyage($destination, $stage->getVoyage());

            $response['btnAddToVoyage'] = $this->renderView('AppBundle:Destination:addAndRemoveDestinationBtn.html.twig',
                [
                    'user'        => $this->getUser(),
                    'destination' => $destination,
                    'stages'      => $stages,
                ]);
        }

        return new JsonResponse($response);
    }


    /**
     * @Route("/{id}/changePosition", name="changePositionStage")
     * @param Stage $stage
     * @param Request $request
     * @return JsonResponse
     */
    public function changeStagePositionAction(Stage $stage, Request $request)
    {
        $newPosition = $request->get('newPosition');
        $oldPosition = $request->get('oldPosition');

        $voyage = $stage->getVoyage();

        /** @var CRUDStage $CRUDStage */
        $CRUDStage = $this->get('crud_stage');
        $CRUDStage->changePosition($stage, $oldPosition, $newPosition);

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
            'maplaceData' => $maplaceData,
            'voyageStats' => $voyageStatsCalculated,
            'statsView'   => $this->renderView('CalculatorBundle:Voyage:dashboardStats.html.twig', ['voyageStats' => $voyageStatsCalculated]),
        ]);
    }

    /**
     * @Route("/changeNbDays", name="changeNbDaysStage")
     * @param Request $request
     * @return JsonResponse
     */
    public function changeNumberDaysAction(Request $request)
    {
        $stageId = $request->get('pk');
        $nbDays = $request->get('value');

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('CalculatorBundle:Stage');

        $stage = $stageRepository->find($stageId);

        /** @var CRUDStage $CRUDStage */
        $CRUDStage = $this->get('crud_stage');
        $CRUDStage->changeNumberDays($stage, $nbDays);

        $stagePrice = $nbDays * $stage->getDestination()->getTotalPrices();

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        $voyage = $stage->getVoyage();
        $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);

        $voyageStatsCalculated = $voyageStats->calculateAllStats($voyage, $stagesSorted);

        return new JsonResponse([
            'nbDays'      => $nbDays,
            'stageId'     => $stage->getId(),
            'stagePrice'  => $stagePrice,
            'voyageStats' => $voyageStatsCalculated,
            'statsView'   => $this->renderView('CalculatorBundle:Voyage:dashboardStats.html.twig', ['voyageStats' => $voyageStatsCalculated])
        ]);
    }

}
