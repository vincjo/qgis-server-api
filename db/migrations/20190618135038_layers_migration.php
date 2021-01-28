<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class LayersMigration extends Migration
{
    public function up()  {
        $this->schema->create('layers', function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('title');
            $table->string('abstract')->nullable();
            $table->string('source')->nullable();
            $table->string('map');
            $table->string('symbol_url')->nullable();
            $table->string('symbol_type')->nullable();
            $table->string('preview_url')->nullable();
            $table->string('protocol')->default('WMS');
            $table->string('project_name');
            /* ex layers_data v */
            $table->string('tablename');
            $table->string('displayfield')->nullable();
            $table->string('geomcolumn');
            $table->string('geomtype'); // POLYGON, LINESTRING, POINT, MULTIPLOLYGON...
            $table->integer('srid');
            $table->json('extent');
            $table->text('sql')->nullable();
            $table->json('columns');
            $table->text('datatable')->nullable();
            $table->integer('datasource_id');    
            /* ^ ex layers_data */        
            $table->integer('folder_id')->default(0);
            $table->integer('role_id')->default(4);
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('layers');
    }
}
