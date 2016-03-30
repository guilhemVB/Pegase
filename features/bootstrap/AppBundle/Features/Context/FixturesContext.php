<?php

namespace AppBundle\Features\Context;

use Behat\Behat\Context\Context;
use AppKernel;
use Behat\Behat\Context\SnippetAcceptingContext;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class FixturesContext implements Context, SnippetAcceptingContext
{

    /** @BeforeSuite */
    public static function before($event)
    {
        $kernel = new AppKernel('dev', true);
        $kernel->boot();

        $application = new Application($kernel);
        $application->setAutoExit(false);
        FeatureContext::runConsole($application, 'doctrine:schema:drop', ['--force' => true, '--full-database' => true]);
        FeatureContext::runConsole($application, 'doctrine:schema:create');
        $kernel->shutdown();
    }

}
