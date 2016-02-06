<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/command")
 */
class CommandController extends Controller
{

    /**
     * @Route("/importCountries", name="importCountries")
     * @return Response
     */
    public function importCountriesAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:import:countries',
            'fileName' => '../web/files/pays.csv',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();

        $content = str_replace("\n", '<br>', $content);

        return new Response($content);
    }

    /**
     * @Route("/importDestinations", name="importDestinations")
     * @return Response
     */
    public function importDestinationsAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'app:import:destinations',
            'fileName' => '../web/files/destinations.csv',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $input = new ArrayInput([
            'command' => 'app:footer',
        ]);
        $application->run($input, $output);

        $content = $output->fetch();

        $content = str_replace("\n", '<br>', $content);

        return new Response($content);
    }

    /**
     * @Route("/updateRates", name="updateRates")
     * @return Response
     */
    public function updateRatesAction()
    {
        $kernel = $this->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(['command' => 'app:update:rates']);

        $output = new BufferedOutput();
        $application->run($input, $output);

        $content = $output->fetch();

        $content = str_replace("\n", '<br>', $content);

        return new Response($content);
    }

}
