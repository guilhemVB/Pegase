<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Destination;
use AppBundle\Repository\DestinationRepository;
use AppBundle\Service\MaplaceMarkerBuilder;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomepageController extends Controller
{

    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepageAction()
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $destinationRepository DestinationRepository */
        $destinationRepository = $em->getRepository('AppBundle:Destination');

        /** @var MaplaceMarkerBuilder $maplaceMarkerBuilder */
        $maplaceMarkerBuilder = $this->get('maplace_marker_builder');

        /** @var Destination[] $allDestinations */
        $allDestinations = $destinationRepository->findAll();

        $maplaceData = $maplaceMarkerBuilder->buildMarkerFromDestinations($allDestinations, ['disableZoom' => true]);

        $lastDestinationsCreated = $destinationRepository->findBy([], ['createdAt' => 'DESC'], 3);

        $countries = [];
        foreach ($allDestinations as $destination) {
            $countries[$destination->getCountry()->getSlug()] = $destination->getCountry();
        }
        sort($countries);

        return $this->render('AppBundle:Homepage:homepage.html.twig',
            [
                'maplaceData'             => json_encode($maplaceData),
                'lastDestinationsCreated' => $lastDestinationsCreated,
                'countries'               => $countries,
            ]);
    }
}
