<?php

namespace AppBundle\Controller;

use AppBundle\Repository\DestinationRepository;
use AppBundle\Service\MaplaceMarkerBuilder;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
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
        $maplaceData = $maplaceMarkerBuilder->buildMarkerFromDestinations($destinationRepository->findAll(), ['disableZoom' => true]);

        return $this->render('AppBundle:Default:homepage.html.twig',
            ['maplaceData' => json_encode($maplaceData),]);
    }
}
