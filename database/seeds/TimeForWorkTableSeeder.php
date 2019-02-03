<?php

use Illuminate\Database\Seeder;
use App\Models\Jobs\TimeForWork;

class TimeForWorkTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        create(TimeForWork::class, ['value' => 'Менее 1 ч']);
        create(TimeForWork::class, ['value' => '1 час']);
        create(TimeForWork::class, ['value' => '2 часа']);
        create(TimeForWork::class, ['value' => '3 часа']);
        create(TimeForWork::class, ['value' => '4 часа']);
        create(TimeForWork::class, ['value' => '5 часов']);
        create(TimeForWork::class, ['value' => '6 часов']);
        create(TimeForWork::class, ['value' => '7 часов']);
        create(TimeForWork::class, ['value' => '8 часов']); 
        create(TimeForWork::class, ['value' => '9 часов']);
        create(TimeForWork::class, ['value' => '10 часов']); 
        create(TimeForWork::class, ['value' => 'Более 10 часов']);
                                       
    }
}
