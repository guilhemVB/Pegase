<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Destination;
use AppBundle\Repository\DestinationRepository;
use AppBundle\Service\MaplaceMarkerBuilder;
use CalculatorBundle\Repository\VoyageRepository;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorDestinations;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorNumberDays;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorPrices;
use CalculatorBundle\Service\Stats\VoyageStats;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomepageController extends Controller
{

    /**
     * @Route("/", name="homepage")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepageAction()
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $destinationRepository DestinationRepository */
        $destinationRepository = $em->getRepository('AppBundle:Destination');

        /** @var $voyageRepository VoyageRepository */
        $voyageRepository = $em->getRepository('CalculatorBundle:Voyage');

        /** @var MaplaceMarkerBuilder $maplaceMarkerBuilder */
        $maplaceMarkerBuilder = $this->get('maplace_marker_builder');

        /** @var Destination[] $allDestinations */
        $allDestinations = $destinationRepository->findBy([], ['latitude' => 'DESC']);

        $maplaceData = $maplaceMarkerBuilder->buildMarkerFromDestinations($allDestinations, ['disableZoom' => true]);

        $lastDestinationsCreated = $destinationRepository->findLastCompleteDestinations();

        $voyages = $voyageRepository->findTypicalVoyages($this->getParameter('typical_voyage_user_id'), 3);

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        $lastVoyagesWithStats = [];
        foreach ($voyages as $voyage) {
            $stages = $voyage->getStages();
            $stats = $voyageStats->calculate($voyage, $stages, [
                new StatCalculatorDestinations(3),
                new StatCalculatorPrices(),
                new StatCalculatorNumberDays(),
            ]);
            $lastVoyagesWithStats[] = [
                'voyage' => $voyage,
                'stats'  => $stats,
            ];
        }

        return $this->render('AppBundle:Homepage:homepage.html.twig',
            [
                'maplaceData'             => json_encode($maplaceData),
                'lastDestinationsCreated' => $lastDestinationsCreated,
                'lastVoyagesWithStats'    => $lastVoyagesWithStats,
            ]);
    }
}
