<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_sources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('query');
            $table->string('source');
            $table->dateTime('anchor_date')->nullable();
            $table->boolean('temporal')->default(0);
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
        Schema::dropIfExists('field_sources');
    }
}
