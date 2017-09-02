<?php

namespace AppBundle\Controller;
//
use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Service\MaplaceMarkerBuilder;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

class ApiMapController extends FOSRestController
{
    /**
     * @Rest\View(serializerGroups={"c-list"})
     * @Rest\Get("/api/map/country/{slug}")
     * @param Country $country
     * @param Request $request
     * @return array
     */
    public function getDestinationsAction(Country $country, Request $request)
    {
        /** @var MaplaceMarkerBuilder $maplaceMarkerBuilder */
        $maplaceMarkerBuilder = $this->get('maplace_marker_builder');
        $maplaceData = $maplaceMarkerBuilder->buildMarkerFromDestinations($country->getDestinations());

        return $maplaceData;
    }

    /**
     * @Rest\View(serializerGroups={"c-list"})
     * @Rest\Get("/api/map/destination/{slug}")
     * @param Destination $destination
     * @param Request $request
     * @return array
     */
    public function getDestinationAction(Destination $destination, Request $request)
    {
        /** @var MaplaceMarkerBuilder $maplaceMarkerBuilder */
        $maplaceMarkerBuilder = $this->get('maplace_marker_builder');
        $maplaceData = $maplaceMarkerBuilder->buildMarkerFromDestination($destination);

        return [$maplaceData];
    }
}
