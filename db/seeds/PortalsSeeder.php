<?php
use Database\Database;
use Phinx\Seed\AbstractSeed;
use \Sigapp\Portals\PortalsModel;

class PortalsSeeder extends AbstractSeed
{
    public function run()
    {
        Database::init();
        PortalsModel::create([
            'title' => 'Portal',
            'sort' => 1,
            'role_id' => 5
        ]);
        PortalsModel::create([
            'title' => 'My maps',
            'sort' => 999,
            'owner' => true,
            'role_id' => 5
        ]);
    }
}