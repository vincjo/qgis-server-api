<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class PortalsMigration extends Migration
{
    public function up()  {
        $this->schema->create('portals', function(Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->integer('sort')->default(999);
            $table->boolean('owner')->default(false);
            $table->integer('gis_id')->default(1);
            $table->integer('role_id')->default(4);
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('portals');
    }
}
