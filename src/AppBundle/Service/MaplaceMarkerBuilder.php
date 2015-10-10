<?php

namespace AppBundle\Service;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\Voyage;

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
            'disableHtml'  => false,
            'disableZoom'  => false,
            'ordereIcons' => false,
        ];
    }

    /**
     * @param Destination $destination
     * @param array $options
     * @param null|int $number
     * @return array
     */
    public function buildMarkerFromDestination(Destination $destination, $options = [], $number = null)
    {
        $options = array_merge($this->defaultOptions(), $options);
        $dataMaplace = [
            'lat'   => $destination->getLatitude(),
            'lon'   => $destination->getLongitude(),
            'title' => $destination->getName(),
        ];

        if (!$options['disableHtml']) {
            $dataMaplace['html'] = $this->twig->render('AppBundle:Destination:googleMarker.html.twig', ['destination' => $destination]);
        }

        if (!$options['disableZoom']) {
            $dataMaplace['zoom'] = 11;
        }
        
        if ($options['ordereIcons'] && !is_null($number)) {
            $iconLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];

            $number = $number % 26;

            $dataMaplace['icon'] = 'http://maps.google.com/mapfiles/marker' . $iconLetters[$number] . '.png';
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
        $number = 0;
        foreach ($destinations as $destination) {
            $dataMaplace[] = $this->buildMarkerFromDestination($destination, $options, $number);
            $number++;
        }

        return $dataMaplace;
    }

    /**
     * @param Stage[] $stages
     * @param array $options
     * @return array
     */
    public function buildMarkerFromStages($stages, $options = [])
    {
        $destinations = [];
        foreach ($stages as $stage) {
            $destinations[] = $stage->getDestination();
        }
        return $this->buildMarkerFromDestinations($destinations, $options);
    }

}
