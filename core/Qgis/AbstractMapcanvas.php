<?php
namespace Core\Qgis;

use Core\Geoprocessing\Extent;

abstract class AbstractMapcanvas
{
    protected $mapcanvas;

    public function getSimpleXmlElement()
    {
        return $this->mapcanvas;
    }

    public function getSrid(): int
    {
        return (integer) $this->mapcanvas->destinationsrs->spatialrefsys->srid[0];
    }

    public function setSrid(int $value): void
    {
        $this->mapcanvas->destinationsrs->spatialrefsys->srid = $value;
    }

    public function getExtent(): array
    {
		$extent = (array) $this->mapcanvas->extent;
		$extent['srid'] = $this->getSrid();
		return ( new Extent($extent) )->transform(3857);
    }

    public function setExtent(array $extent): void
    {
        $extent = ( new Extent($extent) )->transform( $this->getSrid() );
        $this->mapcanvas->extent->xmin = $extent['xmin'];
        $this->mapcanvas->extent->ymin = $extent['ymin'];
        $this->mapcanvas->extent->xmax = $extent['xmax'];
        $this->mapcanvas->extent->ymax = $extent['ymax'];       

    }

    public function getRotation(): float
    {
        return (float) $this->mapcanvas->rotation;
    }

    public function setRotation(float $value): void
    {
        $this->mapcanvas->rotation = $value;
    }
}