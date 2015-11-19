<?php

namespace AppBundle\Command;

use AppBundle\Entity\Country;
use AppBundle\Entity\Destination;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\DestinationRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FindCode3Command extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:country:find-code-3')
            ->setDescription("Permet de récupérer les codes 3 des pays");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $this->findCountryCode3($output);

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

    }

    /**
     * @param OutputInterface $output
     */
    private function findCountryCode3(OutputInterface $output)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var CountryRepository $countryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');
        /** @var Country[] $countries */
        $countries = $countryRepository->findAll();

        foreach ($countries as $country) {
            $code3 = $country->getCode3();
            $code2 = $country->getCode2();
            $name = $country->getName();

            if ($code2 && $code3) {
                continue;
            }

            if (empty($code2)) {
                $output->writeln("<error>PAYS '$name'  --  pas de code 2.</error>");
                continue;
            }

            $output->writeln("<info>PAYS '$name'  --  $code2.</info>");
            $url = "http://restcountries.eu/rest/v1/alpha?codes=" . strtolower($code2);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            curl_close($ch);
            var_dump($data);

            break;

//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            $output = curl_exec($ch);
//            curl_close($ch);



//            if (!(curl_exec($ch) !== FALSE)) {
//                $output->writeln("<error>PAYS '$name'  --  drapeau impossible à récupérer.</error>");
//            }

        }

    }

}
