<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/step")
 */
class StepController extends Controller
{

    /**
     * @Route("create/destination/{id}/add", name="addStep")
     * @param Destination $destination
     * @param Request $request
     * @return JsonResponse
     */
    public function addStageAction(Destination $destination, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $voyages = $user->getVoyages();
        if (count($voyages) === 0) {
            $error = "Can't find voyage";
            return new JsonResponse(['error' => $error], 400);
        }

        $nbDays = $request->get('nbDays');
        if ($nbDays == 0) {
            $error = "nbDays cannot be empty";
            return new JsonResponse(['error' => $error], 400);
        }

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        $voyage = $voyages[0];

        $stage = new Stage();
        $stage->setDestination($destination);
        $stage->setNbDays($nbDays);
        $stage->setPosition(count($voyage->getStages()) - 1);
        $stage->setVoyage($voyage);
        $em->persist($stage);

        $voyage->addStage($stage);
        $em->persist($voyage);

        $em->flush();

        $btnAddToVoyage = $this->renderView('AppBundle:Destination:addAndRemoveDestinationBtn.html.twig',
            [
                'destination' => $destination,
                'stage'       => $stage,
            ]);

        return new JsonResponse(['valid' => true, 'btnAddToVoyage' => $btnAddToVoyage]);
    }


    /**
     * @Route("{id}/remove", name="removeStep")
     * @param Stage $stage
     * @return JsonResponse
     */
    public function removeStageAction(Stage $stage)
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        $destination = $stage->getDestination();

        $em->remove($stage);
        $em->flush();


        $btnAddToVoyage = $this->renderView('AppBundle:Destination:addAndRemoveDestinationBtn.html.twig',
            [
                'destination' => $destination,
            ]);

        return new JsonResponse(['valid' => true, 'btnAddToVoyage' => $btnAddToVoyage]);
    }

}
