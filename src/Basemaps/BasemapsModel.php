<?php
namespace Sigapp\Basemaps;

use Illuminate\Database\Eloquent\Model;

class BasemapsModel extends Model
{
	protected $table = 'basemaps';
    protected $primaryKey = 'id';
    protected $casts = [
        'sort'      => 'integer',
        'main'      => 'boolean',
        'overlays'  => 'array',
    ];

	protected $fillable = [
        'name', 
        'title', 
        'service', // 'WMTS' or 'TMS'
        'attributions', 
        'url', 
        'layer', 
        'format', // 'png' or 'jpeg'
        'style',
        'sort', 
        'main',
        'collection', // 'basemap' or 'overlay'
        'preview_url', 
        'overlays',
        'external_wms'
    ];
}