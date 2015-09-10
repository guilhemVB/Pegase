<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\User;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\StageRepository;
use AppBundle\Service\MaplaceMarkerBuilder;
use AppBundle\Service\Stats\VoyageStats;
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
        $voyages = $user->getVoyages();

        if (count($voyages) == 0) {
            return $this->redirectToRoute('createVoyage');
        }
        $voyage = $voyages[0];

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('AppBundle:Stage');

        $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);

        /** @var MaplaceMarkerBuilder $maplaceMarkerBuilder */
        $maplaceMarkerBuilder = $this->get('maplace_marker_builder');
        $maplaceData = $maplaceMarkerBuilder->buildMarkerFromStages($stagesSorted, ['disableZoom' => true]);

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        return $this->render('AppBundle:Voyage:view.html.twig',
            [
                'voyage'       => $voyage,
                'stagesSorted' => $stagesSorted,
                'maplaceData'  => json_encode($maplaceData),
                'voyageStats'  => $voyageStats->calculate($voyage, $stagesSorted),
            ]);
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

}
