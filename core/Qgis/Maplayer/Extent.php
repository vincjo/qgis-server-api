<?php
namespace Core\Qgis\Maplayer;

use Core\Qgis\AbstractMaplayer;
use Core\Geoprocessing\Extent as Geoprocessing;

class Extent extends AbstractMaplayer
{
    protected $maplayer;
    
    public function __construct(\SimpleXMLElement $maplayer)
    {
        $this->maplayer = $maplayer;
    }

	public function get()
	{
		$extent = (array) $this->maplayer->extent;
		$extent['srid'] = $this->getSrid();
		return ( new Geoprocessing($extent) )->transform(3857);
    }

    public function set(array $extent): void
	{
        $extent = ( new Geoprocessing($extent) )->transform( $this->getSrid() );
        $this->maplayer->extent->xmin = $extent['xmin'];
        $this->maplayer->extent->ymin = $extent['ymin'];
        $this->maplayer->extent->xmax = $extent['xmax'];
        $this->maplayer->extent->ymax = $extent['ymax'];
    }
}