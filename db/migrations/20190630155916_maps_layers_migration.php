<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class MapsLayersMigration extends Migration
{
    public function up()  {
        $this->schema->create('maps_layers', function(Blueprint $table){
            $table->integer('map_id');
            $table->integer('layer_id');
            $table->string('alias')->nullable();
            $table->float('opacity')->default(1.0);
            $table->boolean('visible')->default(true);
            $table->text('filter')->nullable();
            $table->integer('sort')->default(999);
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('maps_layers');
    }
}
