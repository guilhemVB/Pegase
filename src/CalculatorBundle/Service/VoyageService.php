<?php

namespace CalculatorBundle\Service;

use AppBundle\Service\MaplaceMarkerBuilder;
use CalculatorBundle\Entity\Voyage;
use CalculatorBundle\Repository\StageRepository;
use Doctrine\ORM\EntityManager;

class VoyageService
{

    /** @var StageRepository */
    private $stageRepository;

    /** @var MaplaceMarkerBuilder */
    private $maplaceMarkerBuilder;

    public function __construct(EntityManager $em, MaplaceMarkerBuilder $maplaceMarkerBuilder)
    {
        $this->stageRepository = $em->getRepository('CalculatorBundle:Stage');
        $this->maplaceMarkerBuilder = $maplaceMarkerBuilder;
    }

    /**
     * @param Voyage $voyage
     * @return array
     */
    public function buildMaplaceDataFromVoyage(Voyage $voyage)
    {
        $stagesSorted = $this->stageRepository->findBy(['voyage' => $voyage], ['position' => 'ASC']);
        $maplaceData = $this->maplaceMarkerBuilder->buildMarkerFromStages($stagesSorted, ['disableZoom' => true, 'ordereIcons' => true]);

        return array_merge(
            [$this->maplaceMarkerBuilder->buildMarkerFromDestination($voyage->getStartDestination(), ['forceMarkerIcon' => true])],
            $maplaceData);
    }
}
