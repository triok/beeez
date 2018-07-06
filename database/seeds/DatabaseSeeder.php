<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
         $this->call(LaratrustSeeder::class);
         $this->call(JobsSeeder::class);
         $this->call(ModuleSeeder::class);
         $this->call(SocialTableSeeder::class);
    }
}
