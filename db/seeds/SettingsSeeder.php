<?php
use Database\Database;
use Phinx\Seed\AbstractSeed;
use \Sigapp\Settings\SettingsModel;
use Dotenv\Dotenv;

class SettingsSeeder extends AbstractSeed
{
    public function run()
    {
        Database::init();
        SettingsModel::create([
            'name'  => 'application',
            'value' => [
                'name' => 'Sigapp',
                'logo'  => API_URL . 'images/template.logo.svg',
                'extent' => [
                    'xmin' => -604158.2716,
                    'ymin' => 5195271.9385,
                    'xmax' => 966164.0375,
                    'ymax' => 6672646.8212,
                    'srid' => 3857
                ]
            ],
        ]);
    }
}