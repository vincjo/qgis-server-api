<?php
namespace Sigapp\QgisProjects;

use Illuminate\Database\Eloquent\Model;

class QgisProjectsModel extends Model
{
    public $incrementing = false;
	protected $table = 'qgis_projects';
    protected $primaryKey = 'name';
    protected $keyType = 'string';
    
	protected $fillable = [
		'name', 'title', 'version'
	];
	
    public function layers()
    {
        return $this->hasMany('\Sigapp\Layers\LayersModel', 'project_name', 'name');
    }
}