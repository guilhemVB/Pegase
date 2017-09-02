<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Destination;
use AppBundle\Entity\User;
use AppBundle\Service\MaplaceMarkerBuilder;
use CalculatorBundle\Repository\StageRepository;
use Doctrine\ORM\EntityManager;
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
//        /** @var User $user */
//        $user = $this->getUser();
//
//        $stages = [];
//        if (!is_null($user)) {
//            $voyages = $user->getVoyages();
//            if (count($voyages) > 0) {
//
//                /** @var $em EntityManager $em */
//                $em = $this->get('doctrine')->getManager();
//
//                /** @var $stageRepository StageRepository */
//                $stageRepository = $em->getRepository('CalculatorBundle:Stage');
//
//                $stages = $stageRepository->findStagesFromDestinationAndVoyage($destination, $voyages[0]);
//            }
//        }
//
//        $btnAddToVoyage = $this->renderView('AppBundle:Destination:addAndRemoveDestinationBtn.html.twig',
//            [
//                'destination' => $destination,
//                'user'        => $user,
//                'stages'      => $stages,
//            ]);

        return $this->render('AppBundle:Destination:view.html.twig',
            [
                'destination'    => $destination,
//                'btnAddToVoyage' => $btnAddToVoyage
            ]);
    }
}
