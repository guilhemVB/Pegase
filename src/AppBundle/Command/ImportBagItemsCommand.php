<?php

namespace AppBundle\Command;

use AppBundle\Entity\BagItem;
use AppBundle\Repository\BagItemRepository;
use AppBundle\Service\CSVParser;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportBagItemsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('bagItems:import')
            ->setDescription("Permet d'importer et mettre à jour la liste des objets d'un sac à dos")
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

        /** @var BagItemRepository $bagItemRepository */
        $bagItemRepository = $em->getRepository('AppBundle:BagItem');

        $fileName = $input->getArgument('fileName');
        $dataBagItems = CSVParser::extract($fileName, $input, $output);
        $nbBagItems = count($dataBagItems);
        $output->writeln("<info>--- $nbBagItems objets ont été trouvés dans le fichier ---</info>");

        $nbToFlush = 0;
        foreach ($dataBagItems as $dataBagItem) {
            $name = $dataBagItem['nom'];
            $quantity = $dataBagItem['quantité'];

            $bagItem = $bagItemRepository->findOneByName($name);
            if (is_null($bagItem)) {
                $bagItem = new BagItem();
                $bagItem->setName($name);
                $output->writeln("<info>Nouvel objet '$name'</info>");
            }
            $bagItem->setQuantity($quantity);

            $bagItem->setPrice($dataBagItem['prix unitaire en euro']);
            $bagItem->setWeight($dataBagItem['poids unitaire en g']);
            $em->persist($bagItem);

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
