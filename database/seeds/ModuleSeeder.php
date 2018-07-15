<?php

use App\Models\Modules;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{

    public function run()
    {
        $modules  = ['users','profile','jobs','job-categories','job-applications','jobs-manager',
            'job-skills','application-message','payouts','logs'];

        foreach($modules as $module){
            create(Modules::class, ['name' => $module]);
        }
    }
}
