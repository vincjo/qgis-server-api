<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class GisMigration extends Migration {
    public function up()  {
        $this->schema->create('gis', function(Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->integer('role_id')->default(4);
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('gis');
    }
}