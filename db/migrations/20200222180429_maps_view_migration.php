<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class MapsViewMigration extends Migration
{
    public function up()  {
        $this->schema->create('maps_view', function(Blueprint $table){
            $table->integer('map_id');
            $table->integer('user_id');
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('maps_view');
    }
}
