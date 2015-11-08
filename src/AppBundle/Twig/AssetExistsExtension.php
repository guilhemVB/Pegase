<?php

namespace AppBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;

class AssetExistsExtension extends \Twig_Extension
{

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('asset_exists', array($this, 'assetExist')),
        );
    }

    /**
     * @param string $path
     * @return bool
     */
    public function assetExist($path)
    {
        $webRoot = realpath($this->kernel->getRootDir() . '/../web');
        $toCheck = realpath($webRoot . '/' . $path);

        if (!is_file($toCheck)) {
            return false;
        }

        // check if file is well contained in web/ directory (prevents ../ in paths)
        if (strncmp($webRoot, $toCheck, strlen($webRoot)) !== 0) {
            return false;
        }

        return true;
    }

    public function getName()
    {
        return 'asset_exist_extension';
    }
}