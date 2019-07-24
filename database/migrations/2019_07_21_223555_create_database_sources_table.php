<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatabaseSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_sources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique()->index();
            $table->integer('source_id');
            $table->string('source_type');
            $table->timestamps();
        });

        Schema::create('database_sources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('server');
            $table->string('username');
            $table->string('password');
            $table->string('db_name');
            $table->string('db_schema')->nullable();
            $table->integer('port')->nullable();
            $table->timestamps();
        });

        Schema::create('webservice_sources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url');
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
        Schema::dropIfExists('webservice_sources');
        Schema::dropIfExists('database_sources');
        Schema::dropIfExists('data_sources');
    }
}
