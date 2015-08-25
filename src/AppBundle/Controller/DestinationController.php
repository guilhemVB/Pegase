<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class DestinationController extends Controller
{
    /**
     * @ParamConverter("country", options={"mapping": {"slugCountry": "slug"}})
     * @param Country $country
     */
    public function viewCountryAction(Country $country)
    {

    }

    /**
     * @ParamConverter("destination", options={"mapping": {"slugDestination": "slug"}})
     * @param Destination $destination
     */
    public function viewDestinationAction(Destination $destination)
    {

    }

    public function totoAction(){
        echo "Toto";
    }
}
