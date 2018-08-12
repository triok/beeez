<?php

use Illuminate\Database\Seeder;
use App\Models\Jobs\Category;
use App\Models\Jobs\JobCategories;
use App\Models\Jobs\Job;

class JobsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //difficulties
        $difficulties = ['beginner','easy','intermediate','advanced'];
        foreach($difficulties as $difficulty){
            $dif = new \App\Models\Jobs\DifficultyLevel();
            $dif->name = $difficulty;
            $dif->created_at = date('Y-m-d H:i:s');
            $dif->save();
        }
        for ($i=0; $i < 40; $i++) { 
            create(Job::class);
        }
        for ($i=0; $i < 40; $i++) {
         create(JobCategories::class);
        }
    }
}
