<?php
namespace Sigapp\Layers;

interface LayersEntityInterface
{        
    public function getDatatable(string $filter = '');

    public function getDatatableFromExtent(array $extent, string $filter = '');

    public function getExtent(string $filter = '');

    public function getFeatureInfo(string $identifier);

    public function getFeatureInfoFromCoordinates(float $x, float $y, float $zoom, string $filter = '');

    public function getEntityExtent(string $identifier);

    public function getDistinctValues(string $attribute);
}