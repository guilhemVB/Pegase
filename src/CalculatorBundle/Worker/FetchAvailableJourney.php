<?php

namespace CalculatorBundle\Worker;

use AppBundle\Entity\Destination;
use AppBundle\Repository\DestinationRepository;
use CalculatorBundle\Entity\AvailableJourney;
use CalculatorBundle\Repository\AvailableJourneyRepository;
use CalculatorBundle\Service\CRUD\CRUDStage;
use CalculatorBundle\Service\Journey\JourneyFetcherInterface;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;

class FetchAvailableJourney
{

    /** @var EntityManager */
    private $em;

    /** @var JourneyFetcherInterface */
    private $journeyFetcher;

    /** @var AvailableJourneyRepository */
    private $availableJourneyRepository;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(EntityManager $em, JourneyFetcherInterface $journeyFetcher, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->journeyFetcher = $journeyFetcher;
        $this->logger = $logger;

        $this->availableJourneyRepository = $em->getRepository('CalculatorBundle:AvailableJourney');
    }

    /**
     * @param int $nbFetch
     * @throws \Exception
     */
    public function fetch($nbFetch = 10)
    {
        /** @var DestinationRepository $destinationRepository */
        $destinationRepository = $this->em->getRepository('AppBundle:Destination');

        /** @var Destination[] $fromDestinations */
        $fromDestinations = $destinationRepository->findAll();
        /** @var Destination[] $toDestinations */
        $toDestinations = $destinationRepository->findAll();

        $nbAvailableJourneyExtracted = 0;

        try {
            foreach ($fromDestinations as $fromDestination) {
                foreach ($toDestinations as $toDestination) {

                    if ($nbFetch == 0) {
                        return;
                    }

                    if ($fromDestination->getId() == $toDestination->getId()) {
                        continue;
                    }

                    $currentAvailableJourney = $this->availableJourneyRepository->findOneBy(['fromDestination' => $fromDestination, 'toDestination' => $toDestination]);
                    if (!is_null($currentAvailableJourney)) {
                        continue;
                    }

                    $this->logger->info("Fetch data from " . $fromDestination->getName() . " to " . $toDestination->getName());
                    $APIResult = $this->journeyFetcher->fetch($fromDestination, $toDestination);
                    $data = $APIResult['data'];
                    $url = $APIResult['url'];
                    $nbFetch--;

                    if (!$data) {
                        $this->logger->error("Can't fetch data from " . $fromDestination->getName() . " to " . $toDestination->getName() . ". URL : $url");
                        continue;
                    }

                    $availableJourney = $this->extractAvailableJourney($data);

                    if (is_null($availableJourney->getFlyPrices()) && is_null($availableJourney->getBusPrices()) && is_null($availableJourney->getTrainPrices())) {
                        /** @var AvailableJourney $currentReverseAvailableJourney */
                        $currentReverseAvailableJourney = $this->availableJourneyRepository->findOneBy(['fromDestination' => $toDestination, 'toDestination' => $fromDestination]);

                        if (is_null($currentReverseAvailableJourney)) {
                            $this->logger->error("No prices extracted from data from " . $fromDestination->getName() . " to " . $toDestination->getName() . ". URL : $url");
                            continue;
                        } else {
                            $availableJourney->setBusPrices($currentReverseAvailableJourney->getBusPrices());
                            $availableJourney->setBusTime($currentReverseAvailableJourney->getBusTime());
                            $availableJourney->setFlyPrices($currentReverseAvailableJourney->getFlyPrices());
                            $availableJourney->setFlyTime($currentReverseAvailableJourney->getFlyTime());
                            $availableJourney->setTrainPrices($currentReverseAvailableJourney->getTrainPrices());
                            $availableJourney->setTrainTime($currentReverseAvailableJourney->getTrainTime());
                        }
                    }

                    $availableJourney->setFromDestination($fromDestination);
                    $availableJourney->setToDestination($toDestination);

                    $nbAvailableJourneyExtracted++;

                    $this->em->persist($availableJourney);
                    $this->em->flush();

                    sleep(rand(8, 15));
                }
            }
        } catch (\Exception $e) {
            $this->em->flush();
            $this->logger->error($e->getMessage());
            throw $e;
        }

        $this->em->flush();
    }

    /**
     * @param array $data
     * @return AvailableJourney
     * @throws \Exception
     */
    private function extractAvailableJourney($data)
    {
        try {
            $routes = $data['routes'];

            $availableRoutes = [];

            foreach ($routes as $route) {
                $indicativePrice = $route['indicativePrice'];

                if (empty($indicativePrice)) {
                    continue;
                }

                $price = $indicativePrice['price'];
                $duration = $route['duration'];

                $mainType = $this->findMainType($route['segments']);

                if (is_null($mainType)) {
                    continue;
                }

                $availableRoutes[$mainType][] = ['price' => $price, 'duration' => $duration];
            }

            return $this->createAvailableJourney($availableRoutes);
        } catch (\Exception $e) {
            $this->logger->error(serialize($data));
            throw $e;
        }
    }

    /**
     * @param array $availableRoutes
     * @return AvailableJourney
     */
    private function createAvailableJourney($availableRoutes)
    {
        $availableJourney = new AvailableJourney();

        foreach ($availableRoutes as $typeOfTransport => $pricesAndDurations) {

            $bestPriceAndDuration = null;

            foreach ($pricesAndDurations as $priceAndDuration) {
                if (is_null($bestPriceAndDuration) || $priceAndDuration['price'] < $bestPriceAndDuration['price']) {
                    $bestPriceAndDuration = $priceAndDuration;
                }
            }

            if ($typeOfTransport === CRUDStage::FLY) {
                $availableJourney->setFlyPrices($bestPriceAndDuration['price']);
                $availableJourney->setFlyTime($bestPriceAndDuration['duration']);
            } elseif ($typeOfTransport === CRUDStage::TRAIN) {
                $availableJourney->setTrainPrices($bestPriceAndDuration['price']);
                $availableJourney->setTrainTime($bestPriceAndDuration['duration']);
            } elseif ($typeOfTransport === CRUDStage::BUS) {
                $availableJourney->setBusPrices($bestPriceAndDuration['price']);
                $availableJourney->setBusTime($bestPriceAndDuration['duration']);
            }

        }

        return $availableJourney;
    }

    /**
     * @param array $segments
     * @return string
     */
    private function findMainType($segments)
    {
        $distanceByType = [];
        foreach ($segments as $segment) {
            $typeOfTransport = $segment['kind'];
            $distance = $segment['distance'];

            if (!isset($distanceByType[$typeOfTransport])) {
                $distanceByType[$typeOfTransport] = 0;
            }
            $distanceByType[$typeOfTransport] = $distanceByType[$typeOfTransport] + $distance;
        }

        arsort($distanceByType);

        $mainTransportType = key($distanceByType);

        if ($mainTransportType === 'flight') {
            return CRUDStage::FLY;
        }
        if ($mainTransportType === 'bus') {
            return CRUDStage::BUS;
        }
        if ($mainTransportType === 'train') {
            return CRUDStage::TRAIN;
        } else {
            return null;
        }
    }
}
