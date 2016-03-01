<?php

namespace CalculatorBundle\Service\Journey;

use AppBundle\Entity\Destination;

class JourneyFetcherMock implements JourneyFetcherInterface
{

    /**
     * @param Destination $fromDestination
     * @param Destination $toDestination
     * @return array
     * @throws \Exception
     */
    public function fetch(Destination $fromDestination, Destination $toDestination)
    {
        $fileName = $fromDestination->getSlug() . '-' . $toDestination->getSlug();
        $file = file_get_contents(__DIR__ . sprintf("/../../../../features/bootstrap/AppBundle/data/%s.json", $fileName));

        if ($file) {
            return json_decode($file, true);
        } else {
            throw new \Exception("$fileName doesn't exist in the mock.");
        }
    }
}