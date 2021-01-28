<?php
use Database\Database;
use Phinx\Seed\AbstractSeed;
use \Sigapp\Basemaps\BasemapsModel;

class BasemapsSeeder extends AbstractSeed
{
    public function run()
    {
        Database::init();
        BasemapsModel::create([
            'name'  => 'paysage',
            'title' => 'Paysage',
            'service' => 'TMS',
            'attributions' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            'url' => 'http://basemaps.cartocdn.com/rastertiles/voyager_nolabels/{z}/{x}/{y}.png',
            'sort' => 2,
            'main' => 1,
            'collection' => 'displayed',
            'preview_url' => 'images/template.basemap_preview.png',
            'overlays' => [
                (object) [
                    'title'      => 'Paysage LABEL',
                    'service'    => 'TMS',
                    'url'        => 'https => //basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}.png',
                    'layer'      => null,
                    'format'     => null,
                    'style'      => null,
                    'alias'      => 'Toponymes',
                    'icon'       => 'pin_drop',
                    'position'   => 'top',
                    'basemap_id' => 12
                ]
            ],
        ]);
        BasemapsModel::create([
            'name'  => 'paysage_label',
            'title' => 'Paysage Label',
            'service' => 'TMS',
            'attributions' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
            'url' => 'https://basemaps.cartocdn.com/rastertiles/voyager_only_labels/{z}/{x}/{y}.png',
            'sort' => 99999,
            'main' => 0,
            'collection' => 'overlayed',
        ]);
    }
}