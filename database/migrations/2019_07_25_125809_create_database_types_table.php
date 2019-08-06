<?php

use App\DatabaseType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatabaseTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('database_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('database_sources', function (Blueprint $table) {
            $table->unsignedBigInteger('db_type');
            $table->foreign('db_type')->references('id')->on('database_types');
        });


        $this->seedData();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('database_sources', function (Blueprint $table) {
            $table->dropForeign('database_sources_db_type_foreign');
        });
        Schema::dropIfExists('database_types');
    }

    private function seedData()
    {
        DatabaseType::create([
            'name' => 'mysql'
        ]);
        DatabaseType::create([
            'name' => 'sqlserver'
        ]);
        DatabaseType::create([
            'name' => 'postgresql'
        ]);
        DatabaseType::create([
            'name' => 'db2'
        ]);
    }
}
