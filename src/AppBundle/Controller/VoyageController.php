<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/voyage")
 */
class VoyageController extends Controller
{

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dashboardAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        if (count($user->getVoyages()) == 0) {
            // redirect to voyage creation
        }
    }

    /**
     * @Route("/create", name="createVoyage")
     * @return Response
     */
    public function createVoyageAction()
    {
        return $this->render('AppBundle:Voyage:create.html.twig');
    }

}
