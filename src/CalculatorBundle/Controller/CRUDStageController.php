<?php

namespace CalculatorBundle\Controller;

use AppBundle\Entity\Destination;
use AppBundle\Repository\DestinationRepository;
use CalculatorBundle\Entity\Stage;
use AppBundle\Entity\User;
use CalculatorBundle\Entity\Voyage;
use CalculatorBundle\Repository\StageRepository;
use CalculatorBundle\Repository\VoyageRepository;
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
     * @Route("/create/voyage/{voyageId}/destination/{destinationId}/add", name="addStage")
     * @param int $voyageId
     * @param int $destinationId
     * @param Request $request
     * @return JsonResponse
     */
    public function addStageAction($voyageId, $destinationId, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $voyageRepository VoyageRepository */
        $voyageRepository = $em->getRepository('CalculatorBundle:Voyage');

        /** @var Voyage $voyage */
        $voyage = $voyageRepository->findOneBy(['user' => $user, 'id' => $voyageId]);

        if (!$voyage) {
            $error = "Can't find voyage";
            return new JsonResponse(['error' => $error, 'nextUri' => $this->generateUrl('dashboard')], 400);
        }

        $nbDays = $request->get('nbDays');
        if ($nbDays == 0) {
            $error = "nbDays cannot be empty";
            return new JsonResponse(['error' => $error, 'nextUri' => $this->generateUrl('dashboard')], 400);
        }

        /** @var $destinationRepository DestinationRepository */
        $destinationRepository = $em->getRepository('AppBundle:Destination');

        $destination = $destinationRepository->find($destinationId);

        /** @var CRUDStage $CRUDStage */
        $CRUDStage = $this->get('crud_stage');
        $CRUDStage->add($destination, $voyage, $nbDays);

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('CalculatorBundle:Stage');

        $stagesWithThisDestination = $stageRepository->findStagesFromDestinationAndVoyage($destination, $voyage);

        $response = ['success' => true];

        if ($request->get('addBtnAddToVoyage')) {
            $response['btnAddToVoyage'] = $this->renderView('AppBundle:Destination:addAndRemoveDestinationBtn.html.twig',
                [
                    'user'        => $this->getUser(),
                    'destination' => $destination,
                    'stages'      => $stagesWithThisDestination,
                ]);
        } else {

            /** @var VoyageStats $voyageStats */
            $voyageStats = $this->get('voyage_stats');

            $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);
            $voyageStatsCalculated = $voyageStats->calculateAllStats($voyage, $stagesSorted);

            $response['voyageStats'] = $voyageStatsCalculated;
            $response['statsView'] = $this->renderView('CalculatorBundle:Voyage:dashboardStats.html.twig', ['voyageStats' => $voyageStatsCalculated]);
            $response['destinationListView'] = $this->renderView('CalculatorBundle:Voyage:dashboardDestinationsList.html.twig',
                ['stagesSorted' => $stagesSorted, 'voyage' => $voyage, 'voyageStats' => $voyageStatsCalculated]);
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/{stageId}/voyage/{voyageId}/change-transport-type", name="changeTransportTypeStage")
     * @param int $stageId
     * @param int $voyageId
     * @param Request $request
     * @return JsonResponse
     */
    public function changeTransportTypeStageAction($stageId, $voyageId, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $voyageRepository VoyageRepository */
        $voyageRepository = $em->getRepository('CalculatorBundle:Voyage');

        /** @var Voyage $voyage */
        $voyage = $voyageRepository->findOneBy(['user' => $user, 'id' => $voyageId]);

        if (!$voyage) {
            $error = "Can't find voyage";
            return new JsonResponse(['error' => $error, 'nextUri' => $this->generateUrl('dashboard')], 400);
        }

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('CalculatorBundle:Stage');

        /** @var Stage $stage */
        $stage = $stageRepository->find($stageId);

        $transportType = $request->get('transportType');

        /** @var CRUDStage $CRUDStage */
        $CRUDStage = $this->get('crud_stage');
        $CRUDStage->changeTransportType($stage, $transportType);

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);
        $voyageStatsCalculated = $voyageStats->calculateAllStats($voyage, $stagesSorted);

        return new JsonResponse([
            'voyageStats'         => $voyageStatsCalculated,
            'statsView'           => $this->renderView('CalculatorBundle:Voyage:dashboardStats.html.twig', ['voyageStats' => $voyageStatsCalculated]),
            'destinationListView' => $this->renderView('CalculatorBundle:Voyage:dashboardDestinationsList.html.twig',
                ['stagesSorted' => $stagesSorted, 'voyage' => $voyage, 'voyageStats' => $voyageStatsCalculated]),
        ]);
    }


    /**
     * @Route("/{stageId}/voyage/{voyageId}/remove", name="removeStage")
     * @param int $stageId
     * @param int $voyageId
     * @param Request $request
     * @return JsonResponse
     */
    public function removeStageAction($stageId, $voyageId, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $voyageRepository VoyageRepository */
        $voyageRepository = $em->getRepository('CalculatorBundle:Voyage');

        /** @var Voyage $voyage */
        $voyage = $voyageRepository->findOneBy(['user' => $user, 'id' => $voyageId]);

        if (!$voyage) {
            $error = "Can't find voyage";
            return new JsonResponse(['error' => $error, 'nextUri' => $this->generateUrl('dashboard')], 400);
        }

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('CalculatorBundle:Stage');

        /** @var Stage $stage */
        $stage = $stageRepository->find($stageId);

        /** @var CRUDStage $CRUDStage */
        $CRUDStage = $this->get('crud_stage');
        $CRUDStage->remove($stage);

        $destination = $stage->getDestination();
        $voyage = $stage->getVoyage();

        $response = ['success' => true];

        if ($request->get('addBtnAddToVoyage')) {
            $stages = $stageRepository->findStagesFromDestinationAndVoyage($destination, $voyage);

            $response['btnAddToVoyage'] = $this->renderView('AppBundle:Destination:addAndRemoveDestinationBtn.html.twig',
                [
                    'user'        => $this->getUser(),
                    'destination' => $destination,
                    'stages'      => $stages,
                ]);
        } else {

            /** @var VoyageStats $voyageStats */
            $voyageStats = $this->get('voyage_stats');

            $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);
            $voyageStatsCalculated = $voyageStats->calculateAllStats($voyage, $stagesSorted);

            $response['voyageStats'] = $voyageStatsCalculated;
            $response['statsView'] = $this->renderView('CalculatorBundle:Voyage:dashboardStats.html.twig', ['voyageStats' => $voyageStatsCalculated]);
            $response['destinationListView'] = $this->renderView('CalculatorBundle:Voyage:dashboardDestinationsList.html.twig',
                ['stagesSorted' => $stagesSorted, 'voyage' => $voyage, 'voyageStats' => $voyageStatsCalculated]);
        }

        return new JsonResponse($response);
    }


    /**
     * @Route("/{stageId}/voyage/{voyageId}/changePosition", name="changePositionStage")
     * @param int $stageId
     * @param int $voyageId
     * @param Request $request
     * @return JsonResponse
     */
    public function changeStagePositionAction($stageId, $voyageId, Request $request)
    {
        $newPosition = $request->get('newPosition');
        $oldPosition = $request->get('oldPosition');

        /** @var User $user */
        $user = $this->getUser();

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $voyageRepository VoyageRepository */
        $voyageRepository = $em->getRepository('CalculatorBundle:Voyage');

        /** @var Voyage $voyage */
        $voyage = $voyageRepository->findOneBy(['user' => $user, 'id' => $voyageId]);

        if (!$voyage) {
            $error = "Can't find voyage";
            return new JsonResponse(['error' => $error, 'nextUri' => $this->generateUrl('dashboard')], 400);
        }

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('CalculatorBundle:Stage');

        /** @var Stage $stage */
        $stage = $stageRepository->find($stageId);

        $voyage = $stage->getVoyage();

        /** @var CRUDStage $CRUDStage */
        $CRUDStage = $this->get('crud_stage');
        $CRUDStage->changePosition($stage, $oldPosition, $newPosition);

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);
        $voyageStatsCalculated = $voyageStats->calculateAllStats($voyage, $stagesSorted);

        return new JsonResponse([
            'statsView'           => $this->renderView('CalculatorBundle:Voyage:dashboardStats.html.twig', ['voyageStats' => $voyageStatsCalculated]),
            'destinationListView' => $this->renderView('CalculatorBundle:Voyage:dashboardDestinationsList.html.twig',
                ['stagesSorted' => $stagesSorted, 'voyage' => $voyage, 'voyageStats' => $voyageStatsCalculated]),
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

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        $voyage = $stage->getVoyage();
        $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);

        $voyageStatsCalculated = $voyageStats->calculateAllStats($voyage, $stagesSorted);

        return new JsonResponse([
            'statsView'   => $this->renderView('CalculatorBundle:Voyage:dashboardStats.html.twig', ['voyageStats' => $voyageStatsCalculated]),
            'destinationListView' => $this->renderView('CalculatorBundle:Voyage:dashboardDestinationsList.html.twig',
                ['stagesSorted' => $stagesSorted, 'voyage' => $voyage, 'voyageStats' => $voyageStatsCalculated]),
        ]);
    }

}
