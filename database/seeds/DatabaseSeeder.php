<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
         $this->call(LaratrustSeeder::class);
         $this->call(CategoriesTableSeeder::class);  
         $this->call(JobsSeeder::class);
         $this->call(ModuleSeeder::class);
         $this->call(SocialTableSeeder::class);
         $this->call(TeamTypesTableSeeder::class);
         $this->call(PagesTableSeeder::class);
         $this->call(CurrenciesTableSeeder::class);

    }
}
