<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\User;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\StageRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
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
            return $this->redirectToRoute('createVoyage');
        }
        echo 'totot';
    }

    /**
     * @Route("/create", name="createVoyage")
     * @return Response
     */
    public function createVoyageAction()
    {
        /** @var User $user */
        $user = $this->getUser();

        if (count($user->getVoyages()) != 0) {
            return $this->redirectToRoute('dashboard');
        }
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $countryRepository CountryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');

        $countries = $countryRepository->findCountriesWithDestinations();
        return $this->render('AppBundle:Voyage:create.html.twig', ['countries' => $countries]);
    }


    /**
     * @Route("/destination/{id}/add", name="addDestination")
     * @param Destination $destination
     * @param Request $request
     * @return JsonResponse
     */
    public function addDestinationAction(Destination $destination, Request $request)
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
        return new JsonResponse(['valid' => true]);
    }

}
