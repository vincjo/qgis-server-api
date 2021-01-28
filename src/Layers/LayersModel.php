<?php
namespace Sigapp\Layers;

use Illuminate\Database\Eloquent\Model;

class LayersModel extends Model
{
	protected $table = 'layers';
	protected $casts = [
		'srid' 			=> 'integer',
        'extent' 		=> 'array',
        'columns' 		=> 'array',
        'opacity' 		=> 'float', 
        'datasource_id' => 'integer',
        'folder_id' 	=> 'integer',
		'role_id' 		=> 'integer',
    ];
    protected $hidden = ['tablename', 'displayfield', 'geomcolumn', 'geomtype', 'srid', 'extent', 'sql', 'columns', 'datatable', 'opacity', 'datasource_id',];
	protected $fillable = [
		'name', 
		'title', 
		'abstract', 
		'source', 
		'date', 
		'project_name', 
		'map', 
		'symbol_url', 
		'symbol_type', 
		'preview_url', 
		'protocol', 
        'tablename', 
        'displayfield', 
        'geomcolumn', 
        'geomtype', 
        'srid', 
        'extent', 
        'sql', 
        'columns', 
        'datatable', 
        'opacity', 
        'datasource_id',
		'folder_id', 
		'role_id'
	];

    public function datasource()
    {
        return $this->hasOne('\Sigapp\Datasources\DatasourcesModel', 'id', 'datasource_id');
    }
}