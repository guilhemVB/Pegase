<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Country;
use AppBundle\Repository\CountryRepository;
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
        /** @var CountryRepository $countryrepository */
        $countryrepository = $this->em->getRepository('AppBundle:Country');
        return $countryrepository->findOneByName($name);
    }

}
