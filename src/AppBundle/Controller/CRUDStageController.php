<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\User;
use AppBundle\Repository\StageRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/crud/stage")
 */
class CRUDStageController extends Controller
{

    /**
     * @Route("/create/destination/{id}/add", name="addStage")
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
        $stage->setPosition(count($voyage->getStages()) + 1);
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

        return new JsonResponse(['success' => true, 'btnAddToVoyage' => $btnAddToVoyage]);
    }


    /**
     * @Route("/{id}/remove", name="removeStage")
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

        return new JsonResponse(['success' => true, 'btnAddToVoyage' => $btnAddToVoyage]);
    }


    /**
     * @Route("/{id}/changePosition", name="changePositionStage")
     * @param Stage $stage
     * @param Request $request
     * @return JsonResponse
     */
    public function changeStagePositionAction(Stage $stage, Request $request)
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('AppBundle:Stage');

        $newPosition = $request->get('newPosition');
        $oldPosition = $request->get('oldPosition');

        if ($newPosition < $oldPosition) {
            $itPosition = $newPosition;

            while ($itPosition != ($oldPosition - 1)) {
                /** @var Stage $stageIt */
                $stageIt = $stageRepository->findOneBy(['position' => $itPosition]);
                $itPosition++;
                $stageIt->setPosition($itPosition);
                $em->persist($stageIt);
            }
        }

        $stage->setPosition($newPosition);
        $em->persist($stage);
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

}
