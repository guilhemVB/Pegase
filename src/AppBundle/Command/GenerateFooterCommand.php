<?php

namespace AppBundle\Command;

use AppBundle\Repository\CountryRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class GenerateFooterCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:footer')
            ->setDescription("Permet de mettre à jour la liste des pays dans le footer");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        $footerView = __DIR__ . '/../../../app/Resources/views/footer.html.twig';

        $this->removeCurrentFooter($output, $footerView);
        $this->import($output, $footerView);

        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    /**
     * @param OutputInterface $output
     * @param string $footerView
     */
    private function removeCurrentFooter(OutputInterface $output, $footerView)
    {
        $fs = new Filesystem();
        $fs->remove([$footerView]);
        $output->writeln("<info>--- Footer actuel supprimé ---</info>");
    }

    /**
     * @param OutputInterface $output
     * @param string $footerView
     */
    private function import(OutputInterface $output, $footerView)
    {
        /** @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine')->getManager();

        /** @var CountryRepository $countryRepository */
        $countryRepository = $em->getRepository('AppBundle:Country');

        /** @var \Twig_Environment $twig */
        $twig = $this->getContainer()->get('twig');

        $countries = $countryRepository->findCountriesWithDestinations();
        $footerContent = $twig->render('AppBundle:Common:footerTemplate.html.twig', ['countries' => $countries]);

        $fh = fopen($footerView, 'w') or die("ERROR : can't open file");
        fwrite($fh, $footerContent);
        fclose($fh);
        $output->writeln("<info>--- Footer regénéré ---</info>");
    }
}
