<?php
namespace Core\Datastore;


class Drop
{
    public $tablename;
    protected $db;

    public function __construct(string $tablename)
    {
        $this->tablename = $tablename;
        $this->db = Datastore::connect();
    }

    public function deleteTableIfExists()
    {
        return $this->db->exec('
            DROP TABLE IF EXISTS ' .  $this->tablename . ' CASCADE
        ');
    }
}