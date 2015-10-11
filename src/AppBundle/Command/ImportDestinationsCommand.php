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

            if (strlen($description) > 650) {
                $output->writeln("<error>La description de la destination '$name' est trop grande, 650 caractères maximum. La destination n'a pas été importée.</error>");
                continue;
            }

            $country = $countryRepository->findOneByName($countryName);
            if (is_null($country)) {
                $output->writeln("<error>Le pays '$countryName' de la destination '$name' n'a pas été trouvé. La destination n'a pas été importée.</error>");
                continue;
            }

            $destination = $destinationRepository->findOneByName($name);
            if (is_null($destination)) {
                $destination = new Destination();
                $destination->setName($name);
                $output->writeln("<info>Nouvelle destination '$name'</info>");
            }
            $destination->setCountry($country);
            $destination->setDescription($description);
            $destination->setTips($this->extractTips($dataDestination['bons plans']));
            $destination->setPeriods($this->extractPeriods($dataDestination));
            $destination->setPrices($this->extractPrices($dataDestination));
            $destination->setLatitude($dataDestination['latitude']);
            $destination->setLongitude($dataDestination['longitude']);
            $destination->setIsTheCapital($dataDestination['Capitale'] === 'oui');

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

    /**
     * @param array $dataDestination
     * @return array
     */
    private function extractPeriods($dataDestination)
    {
        $periods = DestinationPeriods::getPeriods();
        return [
            $periods[1]  => $dataDestination['janvier'],
            $periods[2]  => $dataDestination['février'],
            $periods[3]  => $dataDestination['mars'],
            $periods[4]  => $dataDestination['avril'],
            $periods[5]  => $dataDestination['mai'],
            $periods[6]  => $dataDestination['juin'],
            $periods[7]  => $dataDestination['juillet'],
            $periods[8]  => $dataDestination['août'],
            $periods[9]  => $dataDestination['septembre'],
            $periods[10] => $dataDestination['octobre'],
            $periods[11] => $dataDestination['novembre'],
            $periods[12] => $dataDestination['décembre'],
        ];
    }

    /**
     * @param array $dataDestination
     * @return array
     */
    private function extractPrices($dataDestination)
    {
        return [
            'accommodation' => $dataDestination["prix de l'hébergement"],
            'life cost'     => $dataDestination['coût de la vie'],
        ];
    }

    /**
     * @param array $tipsStr
     * @return array
     */
    private function extractTips($tipsStr)
    {
        $tips = [];

        foreach (explode('>', $tipsStr) as $tip) {
            $tip = trim($tip);

            if (!empty($tip)) {
                $tips[] = $tip;
            }
        }

        return $tips;
    }
}
