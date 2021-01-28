<?php
namespace Sigapp\Maps;

use \Sigapp\Basemaps\BasemapsModel;
use \Sigapp\Layers\LayersModel;

class MapsEntity
{
    public function __construct($id)
    {
        foreach ( MapsModel::find($id)->toArray() as $k => $v ) {
            $this->{$k} = $v;
        }
        $this->layers = $this->getLayers();
    }

    public function getLayers()
    {
        foreach ( MapsLayersModel::where('map_id', $this->id)->get()->toArray() as $layer ) {
            $layers[] = (object) array_merge(
                $layer, 
                LayersModel::find( $layer['layer_id'] )->toArray() 
            );
        }
        return $layers;
    }
}