<?php
namespace Sigapp\Maps;

use Illuminate\Database\Eloquent\Model;

class MapsModel extends Model
{
	protected $table = 'maps';
	protected $primaryKey = 'id';
    protected $casts = [
		'sort' => 'integer',
		'extent' => 'array',
		'canvas' => 'array',
        'visible' => 'boolean',
        'opacity' => 'float',
		'overlays' => 'array',
		'basemap_id' => 'integer',
		'portal_id' => 'integer',
		'role_id' => 'integer',
		'user_id' => 'integer',
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
    ];
	protected $fillable = [
		'title', 
		'abstract', 
		'sort', 
		'extent', 
		'canvas',
		'visible', 
		'opacity', 
		'overlays', 
		'basemap_id', 
		'portal_id', 
		'role_id', 
		'user_id', 
		'preview_url', 
		'project_name'
	];

	public function layers()
    {
    	return $this->hasMany('Sigapp\Maps\MapsLayersModel', 'map_id');
	}
	
	public function portal()
    {
    	return $this->hasOne('Sigapp\Maps\PortalsModel', 'portal_id', 'id');
    }
}
