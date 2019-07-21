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
            $table->integer('project_id')->index(); // "pid" (project ID) of redcap project
            $table->string('field'); // Name of the field.
            $table->string('label'); // Label shown in REDCap web client
            $table->text('description')->nullable();
            $table->boolean('temporal')->default(0);
            $table->string('category')->nullable();
            $table->string('subcategory')->nullable();
            $table->boolean('identifier')->nullable();
            $table->string('time_format')->nullable();
            $table->string('dictionary');
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
