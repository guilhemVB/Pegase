<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Destination;
use AppBundle\Service\MaplaceMarkerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/destination")
 */
class DestinationController extends Controller
{

    /**
     * @Route("/{slug}", name="destination")
     * @param Destination $destination
     * @return Response
     */
    public function viewAction(Destination $destination)
    {
        /** @var MaplaceMarkerBuilder $maplaceMarkerBuilder */
        $maplaceMarkerBuilder = $this->get('maplace_marker_builder');
        $maplaceData = $maplaceMarkerBuilder->buildMarkerFromDestination($destination, ['disableHtml' => true]);

        return $this->render('AppBundle:Destination:view.html.twig',
            [
                'destination' => $destination,
                'maplaceData' => json_encode([$maplaceData]),
            ]);
    }
}
