<?php

namespace CalculatorBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Repository\CountryRepository;
use CalculatorBundle\Repository\StageRepository;
use CalculatorBundle\Repository\VoyageRepository;
use CalculatorBundle\Service\Stats\VoyageStats;
use CalculatorBundle\Service\VoyageService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/voyage")
 */
class VoyageController extends Controller
{

    /**
     * @Route("/tableau-de-bord/{token}", name="voyage")
     * @param string $token
     * @return RedirectResponse|Response
     */
    public function dashboardAction($token)
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $voyageRepository VoyageRepository */
        $voyageRepository = $em->getRepository('CalculatorBundle:Voyage');

        $voyage = $voyageRepository->findOneBy(['user' => $user, 'token' => $token]);

        if (!$voyage) {
            return $this->redirectToRoute('createVoyage');
        }

        /** @var $countryRepository CountryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');

        $countries = $countryRepository->findCountriesWithDestinations();

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('CalculatorBundle:Stage');

        $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);

        /** @var VoyageService $voyageService */
        $voyageService =$this->get('voyage_service');
        $maplaceData = $voyageService->buildMaplaceDataFromVoyage($voyage);

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        return $this->render('CalculatorBundle:Voyage:dashboard.html.twig',
            [
                'voyage'       => $voyage,
                'stagesSorted' => $stagesSorted,
                'maplaceData'  => json_encode($maplaceData),
                'voyageStats'  => $voyageStats->calculateAllStats($voyage, $stagesSorted),
                'countries'    => $countries,
            ]);
    }

    /**
     * @Route("/nouveau", name="createVoyage")
     * @return Response
     */
    public function createVoyageAction()
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $countryRepository CountryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');

        $countries = $countryRepository->findCountriesWithDestinations();
        return $this->render('CalculatorBundle:Voyage:create.html.twig', ['countries' => $countries]);
    }

}
