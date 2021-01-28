<?php
namespace Sigapp\Gis;

use Illuminate\Database\Eloquent\Model;

class GisFoldersModel extends Model
{
	protected $table = 'gis_folders';
    protected $primaryKey = 'id';
    protected $casts = [
        'parent_id' => 'integer',
        'gis_id' => 'integer',
        'rolde_id' => 'integer',
	];
	
	protected $fillable = [
		'title', 'abstract', 'sort', 'parent_id', 'gis_id', 'rolde_id'
	];

    public function subfolders()
    {
        return $this->hasOne('\Sigapp\Models\Folders', 'parent_id', 'id');
	}   
	
    public function layers()
    {
        return $this->hasMany('\Sigapp\Models\Layers', 'id', 'folder_id');
    }   
}