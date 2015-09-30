<?php

namespace AppBundle\Features\Context;

use AppKernel;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BagItemContext extends CommonContext
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
    }
}
