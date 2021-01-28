<?php
namespace Core\Datastore;

use Symfony\Component\Process\Process;

class Dump extends AbstractDump
{

    protected $maplayer;
    public $project_name;
    public $layer_name;
    public $tablename;
    public $tablename_origin;
    public $geomtype;
    public $key;
    public $file;
    public $projectfile;
    private $params;

    public function __construct(\Core\Qgis\Maplayer $maplayer)
    {
        $this->maplayer         = $maplayer;
        $this->project_name     = $this->getProjectname();
        $this->layer_name       = $this->getLayername();
        $this->tablename        = $this->getTablename();
        $this->tablename_origin = $this->getTablenameOrigin();
        $this->geomtype         = $this->getGeometryType();
        $this->key              = $this->getKey();
        $this->file             = $this->getFile();
        $this->projectfile      = $this->getProjectfile();
        $this->params           = Datastore::getParams();
    }

    public function fileExists(): Dump
    {
        if ( !file_exists($this->file) ) {
            $error = 'Missing local data source : ' . pathinfo( $this->file, PATHINFO_BASENAME);
            throw new DumpException($error, $this->projectfile, $this->layer_name);
        }
        return $this;
    }

    public function process(): Dump
    {
        $process = new Process([
            'ogr2ogr',
            '-f',  'PostgreSQL',
            'PG:' . $this->params['database'], $this->file,
            '-sql', "SELECT * FROM " . $this->tablename_origin,
            '-dialect', 'spatialite',
            '-nlt',  $this->geomtype,
            '-lco', 'SCHEMA=datastore',
            '-lco', 'GEOMETRY_NAME=geom',
            '-lco', 'PRECISION=NO',
            '-lco', 'LAUNDER=NO',
            '-lco', 'FID=' . $this->key,
            '-nln', $this->tablename,
            '-dim',  'XY',
            '-append'
        ]);
        $process->run();
		if (!$process->isSuccessful()) {
            $error = '"' . $this->tablename_origin . '" data source appears to be non-compliant. Layer can\'t be loaded using ogr2ogr. ' . $process->getErrorOutput();
            throw new DumpException($error, $this->projectfile, $this->layer_name);
        }
        return $this;
    }

    public function updateDatasource(): void
    {
        $this->maplayer->setDatasource([
            'projectfile' => $this->projectfile,
            'layer_name'  => $this->layer_name,
            'dbname'      => $this->params['dbname'],
            'tablename'   => '"datastore"."' . $this->tablename . '"',
            'provider'    => 'postgres',
            'host'        => $this->params['host'],
            'port'        => $this->params['port'],
            'user'        => $this->params['user'],
            'password'    => $this->params['password'],
        ]);
    }
}