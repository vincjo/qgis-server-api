<?php
namespace Sigapp\Gis;

use Illuminate\Database\Eloquent\Model;

class ViewFolderLayerModel extends Model
{
	protected $table = 'view_folder_layer';

    protected $casts = [
		'level'         => 'integer',
		'id'            => 'integer',
		'parent_id'     => 'integer',
		'role_id'       => 'integer',
		'created_at'    => 'datetime:Y-m-d h:m:s',
		'updated_at'    => 'datetime:Y-m-d h:m:s',
    ];
    
	protected $fillable = [];
}