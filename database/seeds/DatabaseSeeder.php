<?php

use App\DatabaseSource;
use App\DataSource;
use App\FieldSource;
use App\ProjectMetadata;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    protected $models = [
        ProjectMetadata::class,
        FieldSource::class,
        DataSource::class,
        DatabaseSource::class,

    ];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->cleanDatabase();
        $this->call([
            ProjectMetadataSeeder::class,
            FieldSourceSeeder::class,
            DatabaseSourceSeeder::class
        ]);
    }

    public function cleanDatabase()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach($this->models as $model) {
            $model::truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

}
