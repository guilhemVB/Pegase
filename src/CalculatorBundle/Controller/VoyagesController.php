<?php

namespace CalculatorBundle\Controller;

use AppBundle\Entity\User;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorDestinations;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorNumberDays;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorPrices;
use CalculatorBundle\Service\Stats\VoyageStats;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/voyages")
 */
class VoyagesController extends Controller
{

    /**
     * @Route("", name="voyages")
     */
    public function dashboardAction()
    {
        /** @var User $user */
        $user = $this->getUser();
        $voyages = $user->getVoyages();

        if (count($voyages) == 0) {
            return $this->redirectToRoute('createVoyage');
        }

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        $voyagesWithStats = [];
        foreach ($voyages as $voyage) {
            $stages = $voyage->getStages();
            $stats = $voyageStats->calculate($voyage, $stages, [
                new StatCalculatorDestinations(3),
                new StatCalculatorPrices(),
                new StatCalculatorNumberDays(),
            ]);
            $voyagesWithStats[] = [
                'voyage' => $voyage,
                'stats' => $stats,
            ];
        }

        return $this->render('CalculatorBundle:Voyages:view.html.twig',
            [
                'voyagesWithStats' => $voyagesWithStats
            ]);
    }

}
