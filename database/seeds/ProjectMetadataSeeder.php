<?php

use Illuminate\Database\Seeder;

class ProjectMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach(range(1,20) as $int)
        {
            factory(\App\ProjectMetadata::class, 5)->create([
                'project_id' => $int
            ]);
        }


    }
}
