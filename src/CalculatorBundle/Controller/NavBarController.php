<?php

namespace CalculatorBundle\Controller;

use AppBundle\Entity\User;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorNumberDays;
use CalculatorBundle\Service\Stats\StatCalculators\StatCalculatorPrices;
use CalculatorBundle\Service\Stats\VoyageStats;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/navbar")
 */
class NavBarController extends Controller
{
    /**
     * @Route("/infos", name="navbarInfos")
     * @return Response
     */
    public function viewAction()
    {
        $viewInfos = '';

        /** @var User $user */
        $user = $this->getUser();

        if (!is_null($user)) {
            /** @var VoyageStats $voyageStats */
            $voyageStats = $this->get('voyage_stats');

            $voyages = $user->getVoyages();
            if (count($voyages) > 0) {
                $voyage = $voyages[0];
                $stages = $voyage->getStages();
                $stats = $voyageStats->calculate($voyage, $stages, [new StatCalculatorNumberDays(), new StatCalculatorPrices()]);
                $viewInfos = $this->renderView('AppBundle:Common:navBarInfos.html.twig', $stats);
            }
        }

        return new JsonResponse(['viewInfos' => $viewInfos]);
    }

}
