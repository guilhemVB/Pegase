<?php

namespace CalculatorBundle\Controller;

use AppBundle\Entity\Destination;
use AppBundle\Entity\User;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\DestinationRepository;
use AppBundle\Repository\UserRepository;
use CalculatorBundle\Entity\Voyage;
use CalculatorBundle\Repository\AvailableJourneyRepository;
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
 * @Route("/admin")
 */
class AdminController extends Controller
{

    /**
     * @Route(name="dashboardAdmin")
     * @return Response
     */
    public function dashboardAdminAction()
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $userRepository UserRepository */
        $userRepository = $em->getRepository('AppBundle:User');

        /** @var $countryRepository CountryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');

        /** @var $destinationRepository DestinationRepository */
        $destinationRepository = $em->getRepository('AppBundle:Destination');

        /** @var $voyageRepository VoyageRepository */
        $voyageRepository = $em->getRepository('CalculatorBundle:Voyage');
        $nbTypicalVoyages = count($voyageRepository->findTypicalVoyages($this->getParameter('typical_voyage_user_id')));

        /** @var AvailableJourneyRepository $availableJourneyRepository */
        $availableJourneyRepository = $em->getRepository('CalculatorBundle:AvailableJourney');

        $availableJourneyRepository->findByFromDestinationIdAndToDestinationId(2, 10);

        /** @var array $fromDestinations */
        $fromDestinations = $destinationRepository->findAddDestinationsIdsAndNames();
        /** @var array $toDestinations */
        $toDestinations = $destinationRepository->findAddDestinationsIdsAndNames();

        $cptJourney = 0;
        $missingJourneys = [];
        foreach ($fromDestinations as $fromDestination) {
            foreach ($toDestinations as $toDestination) {

                if ($fromDestination['id'] == $toDestination['id']) {
                    continue;
                }

                $availableJourney = $availableJourneyRepository->findByFromDestinationIdAndToDestinationId($fromDestination['id'], $toDestination['id']);
                if (!empty($availableJourney)) {
                    $cptJourney++;
                } else {
                    $missingJourneys[] = ['from' => $fromDestination, 'to' => $toDestination];
                }
            }
        }

        $countries = $countryRepository->findCountriesWithDestinations();

        return $this->render('CalculatorBundle:Admin:dashboardAdmin.html.twig',
            [
                'nbUser' => count($userRepository->findAll()),
                'countries' => $countries,
                'nbDestinations' => count($destinationRepository->findAll()),
                'nbDestinationsCompletes' => count($destinationRepository->findLastCompleteDestinations(null)),
                'nbTypicalVoyages' => $nbTypicalVoyages,
                'nbVoyages' => count($voyageRepository->findAll()) - $nbTypicalVoyages,
                'nbJourney' => $cptJourney,
                'missingJourneys' => $missingJourneys,
            ]);
    }
}
