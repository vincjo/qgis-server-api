<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class BasemapsMigration extends Migration
{
    public function up()  {
        $this->schema->create('basemaps', function(Blueprint $table){
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('title');
            $table->string('service'); // 'WMTS' or 'TMS'
            $table->text('attributions')->nullable();
            $table->string('url');
            $table->string('layer')->nullable();
            $table->string('format')->nullable(); // 'image/png' or 'image/jpeg'
            $table->string('style')->nullable(); 
            $table->integer('sort')->default(999);
            $table->boolean('main')->default(false);
            $table->string('collection')->default('stored'); // 'displayed', 'stored' or 'overlayed'
            $table->string('preview_url')->nullable();
            $table->json('overlays')->nullable();
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('basemaps');
    }
}
