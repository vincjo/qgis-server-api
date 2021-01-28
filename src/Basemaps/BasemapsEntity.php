<?php
namespace Sigapp\Basemaps;

use \Core\Qgis\Qgis;

class BasemapsEntity
{
    public function create(array $data)
    {
        $exists = BasemapsModel::where([
            [ 'url', '=', $data['url'] ],
            [ 'layer', '=', $data['layer'] ],
        ])->first();
        if ( $exists !== null ) {
            return null;
        }
        $basemap = BasemapsModel::create($data);
        $qgis = new Qgis( PATH_TO_MAPS . $data['project_name'] . '.qgs' );
        $maplayer = $qgis->getMaplayerByName( $data['name'] )->getSimpleXMLElement();
        $qgis->removeMaplayer( $data['name'] );
        $qgis = new Qgis( PATH_TO_MAPS . 'template.basemaps.qgs' );
        $qgis->addMaplayer($maplayer);
        return $basemap;
    }
}