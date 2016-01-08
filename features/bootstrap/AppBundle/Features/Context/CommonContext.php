<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Entity\User;
use CalculatorBundle\Entity\Voyage;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\DestinationRepository;
use AppBundle\Repository\UserRepository;
use CalculatorBundle\Repository\VoyageRepository;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CommonContext extends \PHPUnit_Framework_TestCase implements Context
{

    /** @var  EntityManager */
    protected $em;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->em = $container->get('doctrine')->getManager();
    }

    /**
     * @param string $name
     * @return Country|null
     */
    protected function findCountryByName($name)
    {
        /** @var CountryRepository $countryRepository */
        $countryRepository = $this->em->getRepository('AppBundle:Country');
        return $countryRepository->findOneByName($name);
    }

    /**
     * @param string $userName
     * @return null|User
     */
    protected function findUserByName($userName)
    {
        /** @var UserRepository $userRepository */
        $userRepository = $this->em->getRepository('AppBundle:User');
        return $userRepository->findOneBy(['username' => $userName]);
    }

    /**
     * @param string $name
     * @return Destination
     */
    protected function findDestinationByName($name)
    {
        /** @var DestinationRepository $destinationRepository */
        $destinationRepository = $this->em->getRepository('AppBundle:Destination');
        return $destinationRepository->findOneByName($name);
    }

    /**
     * @param string $name
     * @return Voyage
     */
    protected function findVoyageByName($name)
    {
        /** @var VoyageRepository $voyageRepository */
        $voyageRepository = $this->em->getRepository('CalculatorBundle:Voyage');
        return $voyageRepository->findOneByName($name);
    }

}
