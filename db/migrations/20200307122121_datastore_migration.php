<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class DatastoreMigration extends Migration
{
    public function up()  {
        $this->schema->create('datastore', function(Blueprint $table){
            $table->string('project_name');
            $table->string('layer_name');
            $table->string('tablename');
            $table->string('tablename_origin');
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('datastore');
    }
}
