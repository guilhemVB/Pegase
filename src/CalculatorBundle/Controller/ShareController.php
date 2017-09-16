<?php

namespace CalculatorBundle\Controller;

use CalculatorBundle\Repository\StageRepository;
use CalculatorBundle\Repository\VoyageRepository;
use CalculatorBundle\Service\Stats\VoyageStats;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/partage")
 */
class ShareController extends Controller
{

    /**
     * @Route("/{token}", name="shareVoyage")
     * @param string $token
     * @return RedirectResponse|Response
     */
    public function shareVoyageAction($token)
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $voyageRepository VoyageRepository */
        $voyageRepository = $em->getRepository('CalculatorBundle:Voyage');

        $voyage = $voyageRepository->findOneByToken($token);

        if (is_null($voyage)) {
            return $this->redirectToRoute('createVoyage');
        }

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('CalculatorBundle:Stage');

        $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        return $this->render('CalculatorBundle:Share:shareVoyage.html.twig',
            [
                'voyage'       => $voyage,
                'stagesSorted' => $stagesSorted,
                'voyageStats'  => $voyageStats->calculateAllStats($voyage, $stagesSorted),
            ]);
    }

}
