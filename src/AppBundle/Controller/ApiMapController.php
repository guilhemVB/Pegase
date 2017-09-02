<?php

namespace AppBundle\Controller;
//
use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Repository\DestinationRepository;
use AppBundle\Service\MaplaceMarkerBuilder;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

class ApiMapController extends FOSRestController
{
    /**
     * @Rest\View()
     * @Rest\Get("/api/map/country/{slug}")
     * @param Country $country
     * @param Request $request
     * @return array
     */
    public function getDestinationsBycountryAction(Country $country, Request $request)
    {
        /** @var MaplaceMarkerBuilder $maplaceMarkerBuilder */
        $maplaceMarkerBuilder = $this->get('maplace_marker_builder');
        $maplaceData = $maplaceMarkerBuilder->buildMarkerFromDestinations($country->getDestinations());

        return $maplaceData;
    }

    /**
     * @Rest\View()
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

    /**
     * @Rest\View()
     * @Rest\Get("/api/map/destinations")
     * @param Request $request
     * @return array
     */
    public function getDestinationsAction(Request $request)
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $destinationRepository DestinationRepository */
        $destinationRepository = $em->getRepository('AppBundle:Destination');

        /** @var MaplaceMarkerBuilder $maplaceMarkerBuilder */
        $maplaceMarkerBuilder = $this->get('maplace_marker_builder');
        $maplaceData = $maplaceMarkerBuilder->buildMarkerFromDestinations($destinationRepository->findAll());

        return $maplaceData;
    }
}
