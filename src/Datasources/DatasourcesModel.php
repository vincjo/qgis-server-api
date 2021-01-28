<?php
namespace Sigapp\Datasources;

use Illuminate\Database\Eloquent\Model;

class DatasourcesModel extends Model
{
	protected $table = 'datasources';
	protected $primaryKey = 'id';
	protected $fillable = [
		'id', 'provider', 'host', 'port', 'dbname', 'user', 'password', 'recorded'
	];

	public function layers()
    {
    	return $this->hasMany('Sigapp\Layers\LayersDataModel', 'datasource_id');
    }
}