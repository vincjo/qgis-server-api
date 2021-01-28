<?php
use \Database\Migrations\Migration;
use \Illuminate\Database\Schema\Blueprint;

class ViewFolderLayerMigration extends Migration
{
    public function up()  {

    }

    public function down()  {
        $this->schema->drop('view_folder_layer');
    }
}