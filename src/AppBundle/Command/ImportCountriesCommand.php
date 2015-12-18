<?php

namespace AppBundle\Command;

use AppBundle\Entity\Country;
use AppBundle\Repository\CountryRepository;
use AppBundle\Service\CSVParser;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCountriesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:import:countries')
            ->setDescription("Permet d'importer et mettre à jour la liste des pays")
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

        $fileName = $input->getArgument('fileName');
        $dataCountries = CSVParser::extract($fileName, $input, $output);
        $nbCountries = count($dataCountries);
        $output->writeln("<info>--- $nbCountries pays ont été trouvés dans le fichier ---</info>");

        $nbToFlush = 0;
        foreach ($dataCountries as $dataCountry) {
            $name = $dataCountry['nom'];

            $country = $countryRepository->findOneByName($name);
            if (is_null($country)) {
                $country = new Country();
                $country->setName($name);
                $output->writeln("<info>Nouveau pays '$name'</info>");
            }
            $country->setRedirectToDestination($dataCountry['doit être redirigé vers la destination'] === 'oui');
            $country->setCodeAlpha2($dataCountry['code alpha 2']);
            $country->setCodeAlpha3($dataCountry['code alpha 3']);

            $country = $this->fetchAutomaticDataFromApi($output, $country);
            $em->persist($country);

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
     * @param OutputInterface $output
     * @param Country $country
     * @return Country
     */
    private function fetchAutomaticDataFromApi(OutputInterface $output, Country $country)
    {
        $name = $country->getName();
        $lat = $country->getLatitude();
        $lon = $country->getLongitude();

        $population = $country->getPopulation();

        if (!empty($lat) && !empty($lon) && !empty($population)) {
            return $country;
        }

        $code = $country->getCodeAlpha3();

        if (empty($code)) {
            $output->writeln("<error>Pays '$name'  --  Code Alpha3 inconnu.</error>");
            return $country;
        }

        $url = "http://restcountries.eu/rest/v1/alpha?codes=$code";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $countriesData = curl_exec($ch);
        curl_close($ch);

        $countriesData = json_decode($countriesData, true);

        if (empty($countriesData) || is_null($countriesData[0])) {
            $output->writeln("<error>Pays '$name'  --  Impossible de trouver le pays avec le code '$code'. URL : '$url'.</error>");
            return $country;
        }
        $countryData = $countriesData[0];

        $country->setLatitude($countryData['latlng'][0]);
        $country->setLongitude($countryData['latlng'][1]);
        $country->setPopulation($countryData['population']);


        $output->writeln("<info>Pays '$name'  --  Utilisation de l'API pour récuprer des infos sur le pays.</info>");

        return $country;
    }
}
