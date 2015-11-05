<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Repository\TypicalVoyageRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TypicalVoyageController extends Controller
{

    /**
     * @Route("/ideas-of-travels", name="ideasOfTravels")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function voyagesAction()
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $typicalVoyageRepository TypicalVoyageRepository */
        $typicalVoyageRepository = $em->getRepository('AppBundle:TypicalVoyage');

        $typicalsVoyage = $typicalVoyageRepository->findAllTypicalVoyages();

        return $this->render('AppBundle:TypicalVoyage:view.html.twig',
            ['typicalsVoyage' => $typicalsVoyage,]);
    }
}
