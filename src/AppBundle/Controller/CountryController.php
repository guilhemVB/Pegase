<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends Controller
{
    /**
     * @Route("/country/{slug}", name="country")
     * @param Country $country
     * @return Response
     */
    public function viewCountryAction(Country $country)
    {
        return $this->render('AppBundle:Country:view.html.twig', ['country' => $country]);
    }

}
