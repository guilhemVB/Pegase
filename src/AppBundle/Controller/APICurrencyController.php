<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Currency;
use AppBundle\Repository\CurrencyRepository;
use AppBundle\Repository\DestinationRepository;
use CalculatorBundle\Entity\AvailableJourney;
use AppBundle\Form\Type\CurrencyType;
use CalculatorBundle\Repository\AvailableJourneyRepository;
use CalculatorBundle\Service\CRUD\CRUDAvailableJourney;
use Doctrine\ORM\EntityManager;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\ConstraintViolationList;

class APICurrencyController extends FOSRestController
{

    /**
     * @Rest\View(serializerGroups={"view"})
     * @Rest\Get("/api/currencies")
     */
    public function getCurrenciesAction(Request $request)
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        /** @var $currencyRepository CurrencyRepository */
        $currencyRepository = $em->getRepository('AppBundle:Currency');

        $currencies = $currencyRepository->findAll();

        return $currencies;
    }

    /**
     * @Rest\View(serializerGroups={"view"})
     * @Rest\Get("/api/currency/{id}", requirements = {"id"="\d+"})
     * @param Currency $currency
     * @param Request $request
     * @return Currency
     */
    public function getCurrencyAction(Currency $currency, Request $request)
    {
        return $currency;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/api/currency")
     * @param Request $request
     * @return Currency|\Symfony\Component\Form\Form
     */
    public function postCurrencyAction(Request $request)
    {
        $currency = new Currency();
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        $em->persist($currency);
        $em->flush();

        return $currency;
    }

    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/api/currency/{id}", requirements = {"id"="\d+"})
     * @param Currency $currency
     * @param Request $request
     */
    public function removeCurrencyAction(Currency $currency, Request $request)
    {
        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        if ($currency) {
            $em->remove($currency);
            $em->flush();
        }
    }

    /**
     * @Rest\View()
     * @Rest\Put("/api/currency/{id}", requirements = {"id"="\d+"})
     * @param Currency $currency
     * @param Request $request
     * @return Currency|\Symfony\Component\Form\Form
     */
    public function putCurrencyAction(Currency $currency, Request $request)
    {
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $form;
        }

        /** @var $em EntityManager $em */
        $em = $this->get('doctrine')->getManager();

        $em->persist($currency);
        $em->flush();

        return $currency;
    }

}
