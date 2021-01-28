<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class DatasourcesMigration extends Migration
{
    public function up()  {
        $this->schema->create('datasources', function(Blueprint $table){
            $table->increments('id');
            $table->string('provider');
            $table->string('host')->nullable();
            $table->string('port')->nullable();
            $table->string('dbname');
            $table->string('user')->nullable();
            $table->string('password')->nullable();
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('datasources');
    }
}
