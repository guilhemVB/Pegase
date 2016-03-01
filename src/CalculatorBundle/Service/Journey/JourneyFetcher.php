<?php

namespace CalculatorBundle\Service\Journey;

use AppBundle\Entity\Destination;
use Psr\Log\LoggerInterface;

class JourneyFetcher implements JourneyFetcherInterface
{

    /** @var string */
    private $apiUrl;

    /** @var LoggerInterface */
    private $logger;

    public function __construct($apiUrl, LoggerInterface $logger)
    {
        $this->apiUrl = $apiUrl . "&oPos=%s,%s&dPos=%s,%s";
        $this->logger = $logger;
    }

    /**
     * @param Destination $fromDestination
     * @param Destination $toDestination
     * @return array
     */
    public function fetch(Destination $fromDestination, Destination $toDestination)
    {
        $url = sprintf($this->apiUrl, $fromDestination->getLatitude(), $fromDestination->getLongitude(), $toDestination->getLatitude(), $toDestination->getLongitude());
        $this->logger->info("URL to call : $url");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}