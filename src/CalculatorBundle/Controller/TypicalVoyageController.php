<?php

namespace CalculatorBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CalculatorBundle\Repository\TypicalVoyageRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TypicalVoyageController extends Controller
{

    /**
     * @Route("/idees-de-voyages", name="ideasOfTravels")
     * @return Response
     */
    public function ideasOfTravelsAction()
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $typicalVoyageRepository TypicalVoyageRepository */
        $typicalVoyageRepository = $em->getRepository('CalculatorBundle:TypicalVoyage');

        $typicalsVoyage = $typicalVoyageRepository->findAllTypicalVoyages();

        return $this->render('CalculatorBundle:TypicalVoyage:view.html.twig',
            ['typicalsVoyage' => $typicalsVoyage,]);
    }

    /**
     * @Route("/admin/idees-de-voyages", name="adminIdeasOfTravels")
     * @return Response
     */
    public function adminIdeasOfTravelsAction()
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $typicalVoyageRepository TypicalVoyageRepository */
        $typicalVoyageRepository = $em->getRepository('CalculatorBundle:TypicalVoyage');

        $typicalsVoyage = $typicalVoyageRepository->findAllTypicalVoyages();

        return $this->render('CalculatorBundle:TypicalVoyage:view.html.twig',
            ['typicalsVoyage' => $typicalsVoyage,]);
    }
}
