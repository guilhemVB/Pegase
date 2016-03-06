<?php

namespace CalculatorBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('journeyTime', array($this, 'journeyTimePrinter')),
        );
    }

    public function journeyTimePrinter($timeInMinutes)
    {
        $hours = floor($timeInMinutes / 60);
        $minutes = $timeInMinutes % 60;

        if ($minutes == 0) {
            $minutes = '00';
        } elseif ($minutes < 10) {
            $minutes = '0' . $minutes;
        }

        if ($hours > 0) {
            return sprintf("%sh%s", $hours, $minutes);
        } else {
            return sprintf("%smin", $minutes);
        }
    }

    public function getName()
    {
        return 'app_extension';
    }
}