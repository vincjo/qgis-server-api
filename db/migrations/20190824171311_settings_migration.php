<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class SettingsMigration extends Migration
{
    public function up()  {
        $this->schema->create('settings', function(Blueprint $table){
            $table->string('name');
            $table->json('value');
            $table->timestamps();
        });
    }
    public function down()  {
        $this->schema->drop('settings');
    }
}
