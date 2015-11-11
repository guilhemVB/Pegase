<?php

use Symfony\Component\Yaml\Yaml;

/** @var $container \Symfony\Component\DependencyInjection\ContainerBuilder */
$container;

$dist = Yaml::parse(file_get_contents(__DIR__ . '/parameters.yml.dist'));
$distParameters = $dist['parameters'];

foreach ($distParameters as $parameterName => $default) {
    if (false !== ($value = getenv('sf2.' . $parameterName))) {
        $container->setParameter($parameterName, Yaml::parse($value));
    }
}