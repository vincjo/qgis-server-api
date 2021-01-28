<?php
namespace Sigapp\Layers\IO;

use Symfony\Component\Process\Process;
use \Sigapp\Layers\LayersEntity;

class Ogr extends LayersEntity
{
    use OgrTrait;

    public $id;
    public $layer;

    public function __construct($id, $format, $filter, $extent)
    {
        $this->format = $format;
        $this->filter = $filter;
        $this->inExtent = $extent;
        parent::__construct($id);
    }

    public function process(): Ogr
    {
        $this->createTempdir();
        $process = new Process([
            'ogr2ogr',
            '-f',  $this->getDriver(),
            $this->getDestinationFile(), $this->getDatasource(),
            '-sql', $this->getSqlQuery(),
            '-dialect', 'spatialite',
            '-lco', 'GEOMETRY_NAME=geom',
            '-nln', 'data',
        ]);
        $process->run();
		if (!$process->isSuccessful()) {
            $error = "Exctraction issue. " . $process->getErrorOutput();
            // $error = "Exctraction issue. " . $process->getCommandLine();
            throw new OgrException($error, $this);
        }
        return $this;
    }

    public function create()
    {
        $this->process();
        $this->createQml();
        return $this->getOutput();
    }
}