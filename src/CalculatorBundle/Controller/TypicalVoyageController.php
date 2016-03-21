<?php

namespace CalculatorBundle\Controller;

use CalculatorBundle\Repository\VoyageRepository;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorDestinations;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorNumberDays;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorPrices;
use CalculatorBundle\Service\Stats\VoyageStats;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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

        /** @var $voyageRepository VoyageRepository */
        $voyageRepository = $em->getRepository('CalculatorBundle:Voyage');

        $voyages = $voyageRepository->findTypicalVoyages($this->getParameter('typical_voyage_user_id'));

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

        return $this->render('CalculatorBundle:TypicalVoyage:view.html.twig',
            [
                'voyagesWithStats' => $voyagesWithStats
            ]);
    }

}
