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

    /**
     * @param Destination $destination
     * @return array
     */
    public function buildMarkerFromDestination(Destination $destination)
    {
        return [
            'lat' => $destination->getLatitude(),
            'lon' => $destination->getLongitude(),
            'zoom' => 7,
            'title' => $destination->getName(),
            'html' => $this->twig->render('AppBunlde:Destination:googleMarker.html.twig', ['destination' => $destination]),
        ];
    }

    /**
     * @param Destination[] $destinations
     * @return array
     */
    public function buildMarkerFromDestinations($destinations)
    {
        $dataMaplace = [];
        foreach ($destinations as $destination) {
            $dataMaplace[] = $this->buildMarkerFromDestination($destination);
        }
        return $dataMaplace;
    }

}
