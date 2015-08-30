<?php

namespace AppBundle\Service;

use AppBundle\Entity\Destination;

class MaplaceMarkerBuilder
{

    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    private function defaultOptions()
    {
        return [
            'disableHtml' => false
        ];
    }

    /**
     * @param Destination $destination
     * @param array $options
     * @return array
     */
    public function buildMarkerFromDestination(Destination $destination, $options = [])
    {
        $options = array_merge($this->defaultOptions(), $options);
        $dataMaplace = [
            'lat' => $destination->getLatitude(),
            'lon' => $destination->getLongitude(),
            'zoom' => 11,
            'title' => $destination->getName(),
        ];

        if (!$options['disableHtml']) {
            $dataMaplace['html'] = $this->twig->render('AppBundle:Destination:googleMarker.html.twig', ['destination' => $destination]);
        }

        return $dataMaplace;
    }

    /**
     * @param Destination[] $destinations
     * @param array $options
     * @return array
     */
    public function buildMarkerFromDestinations($destinations, $options = [])
    {
        $dataMaplace = [];
        foreach ($destinations as $destination) {
            $dataMaplace[] = $this->buildMarkerFromDestination($destination, $options);
        }

        return $dataMaplace;
    }

}
