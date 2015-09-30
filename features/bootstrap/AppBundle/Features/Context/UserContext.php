<?php

namespace AppBundle\Features\Context;

use AppKernel;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserContext extends CommonContext
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }
}
