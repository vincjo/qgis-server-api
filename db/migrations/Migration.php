<?php
namespace Database\Migrations;

use Database\{ Database, Datastore };
use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration 
{
	/** 
	 * @var \Illuminate\Database\Capsule\Manager $capsule 
	 */
	public $capsule;
	/** 
	 * @var \Illuminate\Database\Schema\Builder $capsule 
	 */
	public $schema;
	
	public function init()  {
		$init = Database::init();
		$this->schema = $init['schema'];
		$this->db = $init['db'];
	}
}