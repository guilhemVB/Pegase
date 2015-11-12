<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Entity\TypicalVoyage;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\DestinationRepository;
use AppBundle\Repository\TypicalVoyageRepository;
use AppBundle\Service\MaplaceMarkerBuilder;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SiteMapController extends Controller
{

    /**
     * @Route("/sitemap.{_format}", name="sitemap", Requirements={"_format" = "xml"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homepageAction()
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        $urls = [];

        $urls[] = ['loc'        => $this->get('router')->generate('homepage', [], true),
                   'changefreq' => 'weekly',
                   'priority'   => '1.0'];

        $urls[] = ['loc'        => $this->get('router')->generate('ideasOfTravels', [], true),
                   'changefreq' => 'weekly',
                   'priority'   => '0.9'];

        $urls[] = ['loc'        => $this->get('router')->generate('destinationsList', [], true),
                   'changefreq' => 'weekly',
                   'priority'   => '0.9'];

        $urls[] = ['loc'        => $this->get('router')->generate('fos_user_registration_register', [], true),
                   'changefreq' => 'monthly',
                   'priority'   => '0.7'];

        $urls[] = ['loc'        => $this->get('router')->generate('fos_user_security_login', [], true),
                   'changefreq' => 'monthly',
                   'priority'   => '0.7'];

        $urls[] = ['loc'        => $this->get('router')->generate('contact', [], true),
                   'changefreq' => 'monthly',
                   'priority'   => '0.5'];

        $urls[] = ['loc'        => $this->get('router')->generate('fos_user_resetting_request', [], true),
                   'changefreq' => 'monthly',
                   'priority'   => '0.2'];

        /** @var $countryRepository CountryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');
        $countries = $countryRepository->findCountriesWithDestinations();

        /** @var Country $country */
        foreach ($countries as $country) {
            $urls[] = ['loc'        => $this->get('router')->generate('country', ['slug' => $country->getSlug()], true),
                       'changefreq' => 'monthly',
                       'priority'   => '0.7'];

            $destinations = $country->getDestinations();
            foreach ($destinations as $destination) {
                $urls[] = ['loc'        => $this->get('router')->generate('destination', ['slug' => $destination->getSlug()], true),
                           'changefreq' => 'monthly',
                           'priority'   => '0.7'];
            }
        }


        /** @var $typicalVoyageRepository TypicalVoyageRepository */
        $typicalVoyageRepository = $em->getRepository('AppBundle:TypicalVoyage');
        $typicalVoyages = $typicalVoyageRepository->findAll();

        /** @var TypicalVoyage $typicalVoyage */
        foreach ($typicalVoyages as $typicalVoyage) {
            $urls[] = ['loc'        => $this->get('router')->generate('shareVoyage', ['token' => $typicalVoyage->getVoyage()->getToken()], true),
                       'changefreq' => 'monthly',
                       'priority'   => '0.7'];
        }

        return $this->render('AppBundle:SiteMap:sitemap.xml.twig', ['urls' => $urls]);
    }
}



