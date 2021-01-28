<?php
namespace Sigapp\Permalinks;

use Illuminate\Database\Eloquent\Model;

class PermalinksModel extends Model
{
	public $incrementing = false;
	protected $table = 'permalinks';
	protected $casts = [
		'map' => 'array',
		'user_id' => 'integer',
    ];

	protected $fillable = [
		'token', 'map', 'type', 'user_id'
	];
}