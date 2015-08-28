<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Destination;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DestinationController extends Controller
{

    /**
     * @Route("/destination/{slug}", name="destination")
     * @param Destination $destination
     * @return Response
     */
    public function viewDestinationAction(Destination $destination)
    {
        return $this->render('AppBundle:Destination:view.html.twig', ['destination' => $destination]);
    }
}
