<?php
namespace Sigapp\QgisProjects;

use \Core\Qgis\Qgis;
use \Sigapp\Basemaps\BasemapsEntity;
use \Sigapp\Layers\{ LayersModel, LayersEntity };
use \Sigapp\Datasources\DatasourcesEntity;

final class QgisProjectsMigration extends Qgis
{
    public $name;
    protected $projectfile;
    private $options;
    public $errors;

    public function __construct(\Core\File\File $file, array $options = [])
    {
        parent::__construct($file->file);
        $this->options = $this->setOptions($options);
        $this->errors = $file->errors;
    }

    public function store()
    {
        $this
            ->storeQgisProject()
            ->storeLayers()
            ->storeBasemaps();
    }

    private function storeQgisProject()
    {
        $project =  QgisProjectsModel::create( $this->get() );
        $this->name = $project['name'];
        return $this;
    }

    private function storeLayers()
    {
        $maplayers = $this->getMaplayersByProviders(['spatialite', 'postgres', 'oracle']);
        foreach ( $maplayers as $maplayer ) {
            $datasource = ( new DatasourcesEntity( $maplayer->getDatasource() ) )->createIfNotRecorded();
            $layer = $maplayer->get();
            $layer['folder_id'] = $this->options['folder_id'];
            $layer['role_id'] = $this->options['role_id'];
            $layer['datasource_id'] = $datasource;
            $layer = LayersModel::create($layer);
            ( new LayersEntity($layer->id) )->updateDatatableQuery();
        }
        return $this;
    }

    private function storeBasemaps()
    {
        $maplayers = $this->getMaplayersByProviders(['wms']);
        foreach ( $maplayers as $maplayer ) {
            $basemap = $maplayer->get();
            ( new BasemapsEntity )->create($basemap);
        }
        return $this;     
    }

    private function setOptions($options)
    {
        $default = [ 'folder_id' => 0,  'role_id' => 4 ];
        return array_merge($default, $options);
    } 

}