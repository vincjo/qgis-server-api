<?php
namespace Sigapp\Layers\IO;

use Symfony\Component\Filesystem\Filesystem;

trait OgrTrait
{
    public $id;
    public $layer;

    public function getDatasource()
    {
        $db = $this->layer['datasource'];
        return "PG:dbname='". $db->dbname ."' host='". $db->host ."' port='". $db->port ."' user='". $db->user ."' password='". $db->password ."'";
    }

    public function getDestinationFile()
    {
        return $this->tempdir . $this->layer['title'] . '.' . $this->format;
    }

    public function getDriver()
    {
        $drivers = [
            'shp'       => 'ESRI Shapefile',
            'geojson'   => 'GeoJSON',
            'gml'       => 'GML',
            'gpkg'      => 'GPKG',
            'kml'       => 'KML',
            'sqlite'    => 'SQLite'

        ];
        return $drivers[$this->format];   
    }

    public function createTempdir()
    {
        $this->tempdir = PATH_TO_FILES . $this->layer['name'] . '_' . $this->format . '/';
        ( new Filesystem() )->mkdir( $this->tempdir );
        return $this;
    }

    public function getSqlQuery()
    {
		foreach($this->layer['columns'] as $column){
			if ( !$column['excluded'] && strlen($column['name']) > 0 ) {
				$str[] = '"' . $column['name'] . '"';
            }
		}        
        $query = "SELECT " . implode(', ', $str) . ', ' . $this->layer['geomcolumn'] . " FROM " . $this->layer['tablename'];
        if ( !empty($this->inExtent) ) {
            $query .= $this->getProvider()->whereIntersects($this->inExtent);
        }
        $query .= $this->getProvider()->getFilter($this->filter, true);
        $query = preg_replace('#\n|\t|\r#', ' ', trim(utf8_encode($query)));
        return $query;
    }

    public function createQml()
    {
        $this->getMaplayer()->saveAsQml($this->tempdir . $this->layer['title'] . '.qml');
    }

    public function getOutput()
    {
        $zip = new Zip($this->tempdir);
        $output = $zip->addFiles();
        ( new Filesystem )->remove($this->tempdir);
        return $output;
    }
}