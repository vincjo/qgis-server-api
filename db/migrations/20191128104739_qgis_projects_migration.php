<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class QgisProjectsMigration extends Migration
{
    public function up()  {
        $this->schema->create('qgis_projects', function(Blueprint $table){
            $table->string('name')->unique();
            $table->string('title');
            $table->string('version')->nullable();
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('qgis_projects');
    }
}
