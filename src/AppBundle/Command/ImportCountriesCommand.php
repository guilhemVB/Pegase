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
            $description = $dataCountry['description'];

            $country = $countryRepository->findOneByName($name);
            if (is_null($country)) {
                $country = new Country();
                $country->setName($name);
                $output->writeln("<info>Nouveau pays '$name'</info>");
            }
            $country->setDescription($description);
            $country->setTips($dataCountry['bons plans']);
            $em->persist($country);

            $nbToFlush++;
            if($nbToFlush % 50 == 0) {
                $em->flush();
                $em->clear();
            }
        }
        $em->flush();
        $em->clear();
    }
}
