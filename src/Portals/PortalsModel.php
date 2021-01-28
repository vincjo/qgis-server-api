<?php
namespace Sigapp\Portals;

use Illuminate\Database\Eloquent\Model;

class PortalsModel extends Model
{
	protected $table = 'portals';
	protected $primaryKey = 'id';
	protected $casts = [
		'sort' => 'integer',
        'gis_id' => 'integer',
		'role_id' => 'integer',
		'owner'	=> 'boolean'
	];
	
	protected $fillable = [
		'title', 'sort', 'gis_id', 'role_id', 'owner'
	];

	public function maps()
	{
        return $this->hasMany('Sigapp\Maps\MapsModel', 'portal_id')->orderBy('sort');		
	}
}