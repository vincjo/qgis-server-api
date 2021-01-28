<?php
namespace Sigapp\Maps;

use Illuminate\Database\Eloquent\Model;

class MapsViewModel extends Model
{
	public $incrementing = false;
	protected $table = 'maps_view';
    protected $casts = [
		'map_id' => 'integer',
		'user_id' => 'integer',
    ];

	protected $fillable = [
			'map_id', 'user_id'
    ];
}