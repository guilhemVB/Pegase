<?php

namespace AppBundle\Features\Context;


use Behat\Behat\Context\Context;
use AppKernel;
use Behat\Behat\Context\SnippetAcceptingContext;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;

class FeatureContext implements Context, SnippetAcceptingContext
{
    public function __construct()
    {
    }

    /** @BeforeScenario */
    public function before($event)
    {
        // Clean database
        $kernel = new AppKernel("test", true);
        $kernel->boot();
        $this->application = new Application($kernel);
        $this->application->setAutoExit(false);
        $this->runConsole("doctrine:schema:drop", ["--force" => true, "--full-database" => true]);
        $this->runConsole("doctrine:schema:create");
        $kernel->shutdown();
    }

    /**
     * @param string $command
     * @param array $options
     * @return int
     * @throws \Exception
     */
    protected function runConsole($command, $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options = array_merge($options, array('command' => $command));
        return $this->application->run(new ArrayInput($options));
    }
}
