<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class GisFoldersMigration extends Migration
{
    public function up()  {
        $this->schema->create('gis_folders', function(Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->integer('sort')->default(999);
            $table->integer('parent_id')->default(0);
            $table->integer('gis_id')->default(1);
            $table->integer('role_id')->default(4);
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('gis_folders');
    }
}
