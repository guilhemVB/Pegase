<?php

namespace AppBundle\Command;

use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\DestinationRepository;
use AppBundle\Service\CSVParser;
use AppBundle\Service\Tools\DestinationPeriods;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DataCheckerCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('data:checker')
            ->setDescription("Permet de vérifier l'état des donénes en base");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $this->checkCountries($output);
        $this->checkDestinations($output);

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

    }

    /**
     * @param OutputInterface $output
     */
    private function checkCountries(OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var CountryRepository $countryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');
        /** @var Country[] $countries */
        $countries = $countryRepository->findAll();

        foreach ($countries as $country) {
            $destinations = $country->getDestinations();
            $name = $country->getName();

            if (is_null($destinations) || count($destinations) == 0) {

                continue;
                $output->writeln("<error>PAYS '$name'  --  pas de destination.</error>");
            }

            $description = $country->getDescription();

            if (strlen($description) > 650) {
                $output->writeln("<error>PAYS '$name'  --  description trop grande, 650 caractères maximum.</error>");
            }

            if (empty($description)) {
                $output->writeln("<error>PAYS '$name'  --  description non saisie.</error>");
            }

            $tips = $country->getTips();

            if (empty($tips)) {
                $output->writeln("<error>PAYS '$name'  --  bons plans non saisis.</error>");
            }

        }

    }


    /**
     * @param OutputInterface $output
     */
    private function checkDestinations(OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var DestinationRepository $destinationRepository */
        $destinationRepository = $em->getRepository('AppBundle:Destination');
        /** @var Destination[] $destinations */
        $destinations = $destinationRepository->findAll();

        foreach ($destinations as $destination) {
            $name = $destination->getName();

            $country = $destination->getCountry();

            if (is_null($country)) {
                $output->writeln("<error>DESTINATION '$name'  --  pas de pays.</error>");
            }

            $description = $destination->getDescription();

            if (strlen($description) > 650) {
                $output->writeln("<error>DESTINATION '$name'  --  description trop grande, 650 caractères maximum.</error>");
            }

            if (empty($description)) {
                $output->writeln("<error>DESTINATION '$name'  --  description non saisie.</error>");
            }

            $tips = $destination->getTips();

            if (empty($tips)) {
                $output->writeln("<error>DESTINATION '$name'  --  bons plans non saisis.</error>");
            }

            $prices = $destination->getPrices();

            if ($prices['accommodation']) {
                $output->writeln("<error>DESTINATION '$name'  --  prix de l'hébergement non saisis.</error>");
            }

            if ($prices['life cost']) {
                $output->writeln("<error>DESTINATION '$name'  --  coût de la vie non saisis.</error>");
            }

            $lon = $destination->getLongitude();
            if (empty($lon)) {
                $output->writeln("<error>DESTINATION '$name'  --  longitude non saisis.</error>");
            }

            $lat = $destination->getLatitude();
            if (empty($lat)) {
                $output->writeln("<error>DESTINATION '$name'  --  latitude non saisis.</error>");
            }

        }

    }

}
