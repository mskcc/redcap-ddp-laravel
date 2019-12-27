<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValueMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('value_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('field_source_value');
            $table->string('redcap_value');
            $table->timestamps();
            $table->unsignedBigInteger('field_source_id');
            $table->foreign('field_source_id')->references('id')->on('field_sources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('value_mappings');
    }
}
