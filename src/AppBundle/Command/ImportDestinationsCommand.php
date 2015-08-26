<?php

namespace AppBundle\Command;

use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\DestinationRepository;
use AppBundle\Service\CSVParser;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportDestinationsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('destinations:import')
            ->setDescription("Permet d'importer et mettre à jour la liste des destinations")
            ->addArgument('fileName', InputArgument::REQUIRED, 'Nom du fichier csv à importer');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $this->import($input, $output);

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    private function import(InputInterface $input, OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var CountryRepository $countryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');

        /** @var DestinationRepository $destinationRepository */
        $destinationRepository = $em->getRepository('AppBundle:Destination');

        $fileName = $input->getArgument('fileName');
        $dataDestinations = CSVParser::extract($fileName, $input, $output);
        $nbDestinations = count($dataDestinations);
        $output->writeln("<info>--- $nbDestinations destinations ont été trouvés dans le fichier ---</info>");

        $nbToFlush = 0;
        foreach ($dataDestinations as $dataDestination) {
            $countryName = $dataDestination['pays'];
            $name = $dataDestination['nom'];
            $description = $dataDestination['description'];

            $country = $countryRepository->findOneByName($countryName);
            if (is_null($country)) {
                $output->writeln("<error>Le pays '$countryName' de la destination '$name' n'a pas été trouvé. La destination n'a pas été importée.</error>");
                continue;
            }

            $destination = $destinationRepository->findOneByName($name);
            if (is_null($destination)) {
                $destination = new Destination();
                $destination->setName($name);
                ("<info>Nouvelle destination '$name'</info>");
            }
            $destination->setCountry($country);
            $destination->setDescription($description);
            $destination->setPeriods($this->extractPeriods($dataDestination));

            $em->persist($destination);

            $nbToFlush++;
            if ($nbToFlush % 50 == 0) {
                $em->flush();
                $em->clear();
            }
        }
        $em->flush();
        $em->clear();
    }

    private function extractPeriods($dataDestination){

        return [
            "january" => $dataDestination['janvier'],
            "february" => $dataDestination['févrierr'],
            "march" => $dataDestination['mars'],
            "april" => $dataDestination['avril'],
            "may" => $dataDestination['mai'],
            "june" => $dataDestination['juin'],
            "july" => $dataDestination['juillet'],
            "august" => $dataDestination['aout'],
            "september" => $dataDestination['septembre'],
            "october" => $dataDestination['octobre'],
            "november" => $dataDestination['novembre'],
            "december" => $dataDestination['décembre']
        ];
    }
}
