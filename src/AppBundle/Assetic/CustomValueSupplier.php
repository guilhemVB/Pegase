<?php
namespace AppBundle\Assetic;

use Symfony\Bundle\AsseticBundle\DefaultValueSupplier;

class CustomValueSupplier extends DefaultValueSupplier
{
    /**
     * Get values for Assetic
     *
     * @return array
     */
    public function getValues()
    {
        //get the default values
        $values = parent::getValues();

        //get the git version as version
        $values['version']=$this->container->getParameter('git_commit');

        return $values;
    }
}
