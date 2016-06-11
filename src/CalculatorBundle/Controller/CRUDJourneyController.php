<?php

namespace CalculatorBundle\Controller;

use AppBundle\Repository\DestinationRepository;
use CalculatorBundle\Entity\AvailableJourney;
use CalculatorBundle\Repository\AvailableJourneyRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/admin/crud/journey")
 */
class CRUDJourneyController extends Controller
{

    /**
     * @Route("/get", name="getJourney")
     * @param Request $request
     * @return JsonResponse
     */
    public function getJourneyAction(Request $request)
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $destinationRepository DestinationRepository */
        $destinationRepository = $em->getRepository('AppBundle:Destination');

        $fromDestination = $destinationRepository->find($request->get('fromDestinationId'));
        $toDestination = $destinationRepository->find($request->get('toDestinationId'));

        /** @var $availableJourneyRepository AvailableJourneyRepository */
        $availableJourneyRepository = $em->getRepository('CalculatorBundle:AvailableJourney');

        /** @var AvailableJourney $availableJourney */
        $availableJourney = $availableJourneyRepository->findOneBy(['fromDestination' => $fromDestination, 'toDestination' => $toDestination]);


        $response = [
            'id' => '',
            'flyPrices' => '',
            'flyTime' => '',
            'busPrices' => '',
            'busTime' => '',
            'trainTime' => '',
            'trainPrices' => '',
            'fromDestination' => [
                'id' => $fromDestination->getId(),
                'name' => $fromDestination->getName(),
                'latitude' => $fromDestination->getLatitude(),
                'longitude' => $fromDestination->getLongitude(),
            ],
            'toDestination' => [
                'id' => $toDestination->getId(),
                'name' => $toDestination->getName(),
                'latitude' => $toDestination->getLatitude(),
                'longitude' => $toDestination->getLongitude(),
            ],
        ];

        if($availableJourney) {
            $response['id'] = $availableJourney->getId();
            $response['flyPrices'] = $availableJourney->getFlyPrices();
            $response['flyTime'] = $availableJourney->getFlyTime();
            $response['busPrices'] = $availableJourney->getBusPrices();
            $response['busTime'] = $availableJourney->getBusTime();
            $response['trainTime'] = $availableJourney->getTrainTime();
            $response['trainPrices'] = $availableJourney->getTrainPrices();
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/edit", name="editJourney")
     * @param Request $request
     * @return JsonResponse
     */
    public function editJourneyAction(Request $request)
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $availableJourneyRepository AvailableJourneyRepository */
        $availableJourneyRepository = $em->getRepository('CalculatorBundle:AvailableJourney');

        $id = $request->get('availableJourneyId');
        if ($id) {
            $availableJourney = $availableJourneyRepository->find($id);
        } else {
            $availableJourney = new AvailableJourney();

            /** @var $destinationRepository DestinationRepository */
            $destinationRepository = $em->getRepository('AppBundle:Destination');

            $fromDestination = $destinationRepository->find($request->get('fromDestinationId'));
            $toDestination = $destinationRepository->find($request->get('toDestinationId'));

            $availableJourney->setFromDestination($fromDestination);
            $availableJourney->setToDestination($toDestination);
        }

        $allAreNull = true;

        $trainTime = $request->get('trainTime');
        $trainPrices = $request->get('trainPrices');
        if (!empty($trainTime) && !empty($trainPrices)) {
            $allAreNull = false;
            $availableJourney->setTrainTime($trainTime);
            $availableJourney->setTrainPrices($trainPrices);
        } else {
            $availableJourney->setTrainTime(null);
            $availableJourney->setTrainPrices(null);
        }

        $busTime = $request->get('busTime');
        $busPrices = $request->get('busPrices');
        if (!empty($busTime) && !empty($busPrices)) {
            $allAreNull = false;
            $availableJourney->setBusTime($busTime);
            $availableJourney->setBusPrices($busPrices);
        } else {
            $availableJourney->setBusTime(null);
            $availableJourney->setBusPrices(null);
        }

        $flyTime = $request->get('flyTime');
        $flyPrices = $request->get('flyPrices');
        if (!empty($flyTime) && !empty($flyPrices)) {
            $allAreNull = false;
            $availableJourney->setFlyTime($flyTime);
            $availableJourney->setFlyPrices($flyPrices);
        } else {
            $availableJourney->setFlyTime(null);
            $availableJourney->setFlyPrices(null);
        }

        if (!$allAreNull) {
            $em->persist($availableJourney);
            $em->flush();
        }

        return new JsonResponse([]);
    }

}
