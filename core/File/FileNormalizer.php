<?php
namespace Core\File;

use \Core\Qgis\Qgis;
use Symfony\Component\Filesystem\Filesystem;
use Cocur\Slugify\Slugify;

class FileNormalizer
{
    use FileTrait;
    
    public $file;
    private $qgis;

    public function __construct(string $file)
    {
        $this->file = $file;
        $this->qgis = new Qgis( $file );
    }

    public function normalize()
    {
        $this->defineProjectTitle();
        $this->defineDisplayfields();
        $this->qgis->useLayerIdAsName();
        $this->qgis->updateLayerTree();
        $this->rename();
        return $this;
    }

    private function defineProjectTitle()
    {
        $title = $this->qgis->getTitle();
        if ( empty($title) || strlen($title === 0) ) {
            $this->qgis->setTitle( $this->getFilename() );
        }
    }

    private function defineDisplayfields()
    {
        $maplayers = $this->qgis->getMaplayersByProviders(['ogr', 'spatialite', 'postgres', 'oracle']);
        foreach ( $maplayers as $maplayer ) {
            $maplayer->setDisplayfieldFromColumnsDefinition();
        }
    }

    private function rename()
    {
        $string = new Slugify(['separator' => '_']);
        $filename = uniqid( $this->getFilename() . '_');
        $file = $this->getTempdir() . $string->slugify( $filename ) . '.qgs';
        ( new Filesystem )->rename( $this->file, $file );        
        $this->setFile($file);
    }
}