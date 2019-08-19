<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EstablishForeignKeysForRelationships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('field_sources', function (Blueprint $table) {
            $table->dropColumn('data_source');
            $table->unsignedBigInteger('data_source_id');
            $table->foreign('data_source_id')->references('id')->on('data_sources');
        });

        Schema::table('project_metadata', function (Blueprint $table) {
            $table->dropColumn('dictionary');
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
        Schema::table('project_metadata', function (Blueprint $table) {
            $table->dropForeign('project_metadata_field_source_id_foreign');
            $table->dropColumn('field_source_id');
            $table->string('dictionary');
        });

        Schema::table('field_sources', function (Blueprint $table) {
            $table->dropForeign('field_sources_data_source_id_foreign');
            $table->dropColumn('data_source_id');
            $table->string('data_source');
        });
    }
}
