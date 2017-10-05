<?php

namespace CalculatorBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Repository\CountryRepository;
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
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class CRUDStageController extends FOSRestController
{
    /**
     * @Rest\View()
     * @Rest\Post("/api/stage/destination")
     * @param Request $request
     * @return array
     */
    public function postStageDestinationAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var VoyageRepository $voyageRepository */
        $voyageRepository = $this->getDoctrine()->getRepository(Voyage::class);
        /** @var Voyage $voyage */
        $voyage = $voyageRepository->findOneBy(['user' => $user, 'id' => $request->get('voyageId')]);

        if (empty($voyage)) {
            return new JsonResponse(['message' => 'Voyage not found'], Response::HTTP_NOT_FOUND);
        }

        /** @var DestinationRepository $destinationRepository */
        $destinationRepository = $this->getDoctrine()->getRepository(Destination::class);
        $destination = $destinationRepository->find($request->get('destinationId'));

        if (empty($destination)) {
            return new JsonResponse(['message' => 'Destination not found'], Response::HTTP_NOT_FOUND);
        }

        $nbDays = $request->get('nbDays');
        if (empty($nbDays) || $nbDays == 0) {
            return new JsonResponse(['message' => "nbDays can't be empty"], Response::HTTP_NOT_FOUND);
        }

        /** @var CRUDStage $CRUDStage */
        $CRUDStage = $this->get('crud_stage');
        $CRUDStage->addDestination($destination, $voyage, $nbDays);

        return new JsonResponse(['message' => 'Stage created'], Response::HTTP_CREATED);
    }


    /**
     * @Route("/create/voyage/{voyageId}/destination/{destinationId}/add", name="addStageDestination")
     * @param int $voyageId
     * @param int $destinationId
     * @param Request $request
     * @return JsonResponse
     */
    public function addStageDestinationAction($voyageId, $destinationId, Request $request)
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
        $CRUDStage->addDestination($destination, $voyage, $nbDays);

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('CalculatorBundle:Stage');

        $response = ['success' => true];

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);
        $voyageStatsCalculated = $voyageStats->calculateAllStats($voyage, $stagesSorted);

        $response['voyageStats'] = $voyageStatsCalculated;
        $response['statsView'] = $this->renderView('CalculatorBundle:Voyage:dashboardStats.html.twig', ['voyageStats' => $voyageStatsCalculated]);
        $response['destinationListView'] = $this->renderView('CalculatorBundle:Voyage:dashboardDestinationsList.html.twig',
            ['stagesSorted' => $stagesSorted, 'voyage' => $voyage, 'voyageStats' => $voyageStatsCalculated]);

        return new JsonResponse($response);
    }

    /**
     * @Route("/create/voyage/{voyageId}/country/{countryId}/add", name="addStageCountry")
     * @param int $voyageId
     * @param int $countryId
     * @param Request $request
     * @return JsonResponse
     */
    public function addStageCountryAction($voyageId, $countryId, Request $request)
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

        /** @var $countryRepository CountryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');

        $country = $countryRepository->find($countryId);

        /** @var CRUDStage $CRUDStage */
        $CRUDStage = $this->get('crud_stage');
        $CRUDStage->addCountry($country, $voyage, $nbDays);

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('CalculatorBundle:Stage');

        $response = ['success' => true];

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);
        $voyageStatsCalculated = $voyageStats->calculateAllStats($voyage, $stagesSorted);

        $response['voyageStats'] = $voyageStatsCalculated;
        $response['statsView'] = $this->renderView('CalculatorBundle:Voyage:dashboardStats.html.twig', ['voyageStats' => $voyageStatsCalculated]);
        $response['destinationListView'] = $this->renderView('CalculatorBundle:Voyage:dashboardDestinationsList.html.twig',
            ['stagesSorted' => $stagesSorted, 'voyage' => $voyage, 'voyageStats' => $voyageStatsCalculated]);

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

        $voyage = $stage->getVoyage();

        $response = ['success' => true];

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);
        $voyageStatsCalculated = $voyageStats->calculateAllStats($voyage, $stagesSorted);

        $response['voyageStats'] = $voyageStatsCalculated;
        $response['statsView'] = $this->renderView('CalculatorBundle:Voyage:dashboardStats.html.twig', ['voyageStats' => $voyageStatsCalculated]);
        $response['destinationListView'] = $this->renderView('CalculatorBundle:Voyage:dashboardDestinationsList.html.twig',
            ['stagesSorted' => $stagesSorted, 'voyage' => $voyage, 'voyageStats' => $voyageStatsCalculated]);

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
