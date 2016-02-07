<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Destination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/vignette")
 */
class VignetteController extends Controller
{

    /**
     * @Route("/{slug}", name="vignette")
     * @param Destination $destination
     * @return Response
     */
    public function getAction(Destination $destination)
    {
        return $this->render('AppBundle:Vignette:destination.html.twig',
            [
                'destination'    => $destination,
            ]);
    }

}
