<?php
namespace Core\Qgis\Maplayer;

use Core\Qgis\AbstractMaplayer;
use Core\Qgis\Maplayer\Datasource\{ Db, Ogr, Wms };

class Datasource extends AbstractMaplayer
{
	protected $maplayer; 

    public function __construct(\SimpleXMLElement $maplayer)
    {
		$this->maplayer = $maplayer;
    }

    public function get(): array
    {
        switch ( $this->getProvider() ) :
            case 'spatialite': 
			case 'oracle':     
			case 'postgres':   return ( new Db( $this->getSimpleXMLElement() ) )->get();
			case 'wms':        return ( new Wms( $this->getSimpleXMLElement() ) )->get();
            case 'ogr':        return ( new Ogr( $this->getSimpleXMLElement() ) )->get();
        endswitch;
	}
	
    public function set(array $datasource): void
    {
        $provider = isset($datasource['provider']) ? $datasource['provider'] : $this->getProvider();
        switch ( $provider ) :
            case 'spatialite': 
			case 'oracle':     
			case 'postgres':    
				( new Db( $this->getSimpleXMLElement() ) )->set($datasource);
				break;
			case 'ogr':        
				( new Ogr( $this->getSimpleXMLElement() ) )->set($datasource);
				break;
        endswitch;
    }
}