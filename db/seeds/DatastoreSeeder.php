<?php
use Database\Database;
use Phinx\Seed\AbstractSeed;

class DatastoreSeeder extends AbstractSeed
{
    public function run()
    {
        Database::connect()->exec(<<<SQL
            CREATE EXTENSION postgis;
            CREATE SCHEMA datastore;
        SQL);
    }
}