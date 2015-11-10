<?php

namespace AppBundle\Command;

use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\DestinationRepository;
use AppBundle\Twig\AssetExistsExtension;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportCountriesListCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('export:countries')
            ->setDescription("Permet de générer la liste des pays qui seront affichage dans le footer");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var CountryRepository $countryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');

        $countries = $countryRepository->findCountriesWithDestinations();
        foreach ($countries as $country) {
            $name = $country->getName();
            $slug = $country->getSlug();
            $output->writeln('<comment>' .
                "<div class=\"col-md-1 col-sm-2 col-xs-3\"><a href=\"{{ path('country', {'slug' : '$slug' }) }}\">$name</a></div>" .
                '</comment>');
        }

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

    }

}
