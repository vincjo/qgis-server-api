<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class MapsMigration extends Migration
{
    public function up()  {
        $this->schema->create('maps', function(Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->string('abstract')->nullable();
            $table->json('extent');
            $table->json('canvas')->nullable();
            $table->boolean('visible')->default(true);
            $table->float('opacity')->default(1.0);
            $table->json('overlays')->nullable();
            $table->integer('sort')->default(999);
            $table->string('preview_url')->nullable();
            $table->integer('basemap_id')->default(0);
            $table->integer('portal_id')->default(0);
            $table->string('project_name')->nullable();
            $table->integer('role_id')->default(4);
            $table->integer('user_id')->nullable();
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('maps');
    }
}