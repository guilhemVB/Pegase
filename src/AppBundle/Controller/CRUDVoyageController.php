<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Traveller;
use AppBundle\Entity\User;
use AppBundle\Entity\Voyage;
use AppBundle\Repository\DestinationRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/voyage/crud")
 */
class CRUDVoyageController extends Controller
{

    /**
     * @Route("/create", name="voyageCRUDCreate")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request)
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $destinationRepository DestinationRepository */
        $destinationRepository = $em->getRepository('AppBundle:Destination');

        $name = $request->get('name');
        $deparatureDate = $request->get('deparatureDate');
        $destinationId = $request->get('destinationId');
        $nbTraveller = $request->get('nbTraveller');

        $voyage = new Voyage();
        $voyage->setUser($this->getUser());
        $voyage->setName($name);
        $voyage->setStartDate(new \DateTime($deparatureDate));
        $voyage->setStartDestination($destinationRepository->find($destinationId));

        for ($i = 0; $i < $nbTraveller; $i++) {
            $traveller = new Traveller();
            $traveller->setName('Voyageur ' . ($i + 1));
            $traveller->setVoyage($voyage);
            $voyage->addTraveller($traveller);

            $em->persist($traveller);
        }

        $em->persist($voyage);
        $em->flush();

        return new JsonResponse(['success' => true, 'nextUri' => $this->generateUrl('dashboard')]);
    }

}
