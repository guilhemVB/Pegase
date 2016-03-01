<?php

namespace CalculatorBundle\Service\Journey;

use AppBundle\Entity\Destination;

class JourneyFetcher implements JourneyFetcherInterface
{

    /** @var string */
    private $apiUrl;

    public function __construct($apiUrl)
    {
        $this->apiUrl = "$apiUrl&oPos=%s,%s&dPos=%s,%s";
    }

    /**
     * @param Destination $fromDestination
     * @param Destination $toDestination
     * @return array
     */
    public function fetch(Destination $fromDestination, Destination $toDestination)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, sprintf($this->apiUrl, $fromDestination->getLatitude(), $fromDestination->getLongitude(), $toDestination->getLatitude(), $toDestination->getLongitude()));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}