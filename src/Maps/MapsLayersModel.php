<?php
namespace Sigapp\Maps;

use Illuminate\Database\Eloquent\Model;

class MapsLayersModel extends Model
{
	public $incrementing = false;
	protected $table = 'maps_layers';
	protected $casts = [
		'map_id' => 'integer',
        'layer_id' => 'integer',
        'sort' => 'integer',
        'opacity' => 'float',
        'visible' => 'boolean',
	];

	protected $fillable = [
		'map_id', 'layer_id', 'alias', 'sort', 'opacity', 'visible', 'filter'
	];
}