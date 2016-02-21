<?php

namespace AppBundle\Features\Context;

use AppBundle\Entity\Currency;
use AppKernel;
use Behat\Gherkin\Node\TableNode;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CurrencyContext extends CommonContext
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }

    /**
     * @Given les monnaies :
     */
    public function lesMonnaies(TableNode $tableCurrencies)
    {
        foreach ($tableCurrencies as $currencyRow) {
            $currency = new Currency();
            $currency->setName($currencyRow['nom'])
            ->setCode($currencyRow['code']);

            $this->em->persist($currency);
        }
        $this->em->flush();
    }


}
