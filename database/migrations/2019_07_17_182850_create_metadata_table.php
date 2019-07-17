<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_metadata', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('project_id')->index();
            $table->string('field');
            $table->string('label');
            $table->text('description');
            $table->boolean('temporal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_metadata');
    }
}
