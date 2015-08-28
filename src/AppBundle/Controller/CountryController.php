<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
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
        return $this->render('AppBundle:Country:view.html.twig', ['country' => $country]);
    }

}
