<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Destination;
use AppBundle\Entity\Stage;
use AppBundle\Entity\User;
use AppBundle\Entity\Voyage;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\StageRepository;
use AppBundle\Repository\VoyageRepository;
use AppBundle\Service\MaplaceMarkerBuilder;
use AppBundle\Service\Stats\VoyageStats;
use AppBundle\Service\VoyageService;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/share")
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
        $voyageRepository = $em->getRepository('AppBundle:Voyage');

        $voyage = $voyageRepository->findOneByToken($token);

        if (is_null($voyage)) {
            return $this->redirectToRoute('createVoyage');
        }

        /** @var $stageRepository StageRepository */
        $stageRepository = $em->getRepository('AppBundle:Stage');

        $stagesSorted = $stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);

        /** @var VoyageService $voyageService */
        $voyageService = $this->get('voyage_service');
        $maplaceData = $voyageService->buildMaplaceDataFromVoyage($voyage);

        /** @var VoyageStats $voyageStats */
        $voyageStats = $this->get('voyage_stats');

        return $this->render('AppBundle:Share:shareVoyage.html.twig',
            [
                'voyage'       => $voyage,
                'stagesSorted' => $stagesSorted,
                'maplaceData'  => json_encode($maplaceData),
                'voyageStats'  => $voyageStats->calculateAllStats($stagesSorted),
            ]);
    }

}
