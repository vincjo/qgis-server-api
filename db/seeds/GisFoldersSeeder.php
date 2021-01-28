<?php
use Database\Database;
use Phinx\Seed\AbstractSeed;
use \Sigapp\Gis\GisFoldersModel;

class GisFoldersSeeder extends AbstractSeed
{
    public function run()
    {
        Database::init();
        GisFoldersModel::create([
            'title' => 'SIG',
            'sort' => 1,
            'parent_id' => 0,
            'role_id' => 5
        ]);
    }
}