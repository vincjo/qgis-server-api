<?php
namespace Core\Datastore;

use \Database\Database;
use \Core\Qgis\Qgis;

abstract class AbstractDatastore
{
    protected $db;
    protected $params;

    protected function connect()
    {
        $this->db = Database::connect();
    }
}