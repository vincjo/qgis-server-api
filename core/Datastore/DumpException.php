<?php
namespace Core\Datastore;

use \Core\Qgis\Qgis;

class DumpException extends \Exception
{
    public function __construct(string $message, string $projectfile, string $layername)
    {
        $this->message = '[DATASTORE ERROR] ' . $message;
        $qgis = new Qgis($projectfile);
        $qgis->removeMaplayer($layername);
    }
}