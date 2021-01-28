<?php
namespace Core\Datastore;

use Illuminate\Database\Eloquent\Model;

class DatastoreModel extends Model
{
    use \Awobaz\Compoships\Compoships;

    public $incrementing = false;
	protected $table = 'datastore';
	
	protected $fillable = [
		'project_name', 'layer_name', 'tablename', 'tablename_origin' 
	];
	
    public function layer()
    {
        return $this->hasOne('\Sigapp\Layers\LayersModel', ['project_name', 'project_name'], ['layer_name', 'name'] );
    }

    public function properties()
    {
        return $this->hasOne('\Sigapp\Layers\LayersDataModel', ['project_name', 'project_name'], ['layer_name', 'name'] );
    }

    public function projects()
    {
        return $this->hasMany('\Sigapp\Layers\QgisProjectsModel', 'project_name', 'name');
    }
}