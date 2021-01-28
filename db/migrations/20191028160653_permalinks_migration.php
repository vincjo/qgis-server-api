<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class PermalinksMigration extends Migration
{
    public function up()  {
        $this->schema->create('permalinks', function(Blueprint $table){
            $table->string('token')->unique();
            $table->json('map');
            $table->string('type')->default('link'); // link, print or shortcut
            $table->integer('user_id');
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('permalinks');
    }
}
