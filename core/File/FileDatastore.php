<?php
namespace Core\File;

use \Core\Qgis\Qgis;
use \Core\Datastore\Datastore;

class FileDatastore
{
    public $errors;

    public function __construct(string $projectfile)
    {
        $this->qgis = new Qgis($projectfile);
        $this->errors = [];
    }

    public function dump()
    {
        $maplayers = $this->qgis->getMaplayersByProviders(['ogr', 'spatialite']);
        foreach ($maplayers as $maplayer) {
            try {
                Datastore::dump($maplayer);
            } catch (\Exception $e) {
                array_push( $this->errors, $e->getMessage() );
            }
        }
        return $this;
    }
}