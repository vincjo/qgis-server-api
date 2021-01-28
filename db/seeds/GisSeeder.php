<?php
use Database\Database;
use Phinx\Seed\AbstractSeed;
use \Sigapp\Gis\GisModel;

class GisSeeder extends AbstractSeed
{
    public function run()
    {
        Database::init();
        GisModel::create([
            'title' => 'main',
            'role_id' => 5
        ]);
    }
}