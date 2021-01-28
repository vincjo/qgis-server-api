<?php
namespace Sigapp\Settings;

use Illuminate\Database\Eloquent\Model;

class SettingsModel extends Model
{
	public $incrementing = false;
	protected $table = 'settings';
	protected $primaryKey = 'name';
    protected $casts = [
		'value' => 'array',
    ];

	protected $fillable = [
			'name', 'value'
    ];
}