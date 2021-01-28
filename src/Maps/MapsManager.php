<?php
namespace Sigapp\Maps;

use \Sigapp\Layers\LayersModel;
use Symfony\Component\Filesystem\Filesystem;

class MapsManager
{
    public function __construct(array $map)
    {
        $this->map = $map;
        $this->layers = $map['layers'];
    }

    public function create()
    {
        $map = MapsModel::create($this->map);
        $i = 1;
        foreach ($this->layers as $layer) {
            $layer['map_id'] = $map->id;
            $layer['layer_id'] = $layer['id'] ?? $layer['layer_id'];
            $layer['sort'] = $i;
            MapsLayersModel::create($layer);
            $i++;
        }
        return $map;
    }

    public static function deleteById($id)
    {
        MapsModel::where('id', $id)->delete();
        MapsLayersModel::where('map_id', $id)->delete();
        ( new Filesystem )->remove( PATH_TO_IMAGES . 'map_preview_' . $id . '.png' );
    }
}