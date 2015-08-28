<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Service\MaplaceMarkerBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/country")
 */
class CountryController extends Controller
{
    /**
     * @Route("/{slug}", name="country")
     * @param Country $country
     * @return Response
     */
    public function viewAction(Country $country)
    {
        /** @var MaplaceMarkerBuilder $maplaceMarkerBuilder */
        $maplaceMarkerBuilder = $this->get('maplace_marker_builder');
        $maplaceData = $maplaceMarkerBuilder->buildMarkerFromDestinations($country->getDestinations());

        return $this->render('AppBundle:Country:view.html.twig',
            [
                'country' => $country,
                'maplaceData' => json_encode($maplaceData),
            ]);
    }

}
