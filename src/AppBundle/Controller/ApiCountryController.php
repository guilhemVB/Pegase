<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Country;
use AppBundle\Repository\CountryRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;

class ApiCountryController extends FOSRestController
{
    /**
     * @Rest\View(serializerGroups={"c-list"})
     * @Rest\Get("/api/countries")
     */
    public function getCountriesAction(Request $request)
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();
        /** @var $countryRepository CountryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');
        $countries = $countryRepository->findAll();

        return $countries;
    }


    /**
     * @Rest\View(serializerGroups={"c-complete", "d-list"})
     * @Rest\Get("/api/countries/{slug}")
     * @param Country $country
     * @param Request $request
     * @return Country
     */
    public function getCountryAction(Country $country, Request $request)
    {
        return $country;
    }
}
