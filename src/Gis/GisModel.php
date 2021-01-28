<?php
namespace Sigapp\Gis;

use Illuminate\Database\Eloquent\Model;

class GisModel extends Model
{
	protected $table = 'gis';
	protected $primaryKey = 'id';
    protected $casts = [
        'rolde_id' => 'integer',
	];

	protected $fillable = [
		'title', 'role_id'
	];
}