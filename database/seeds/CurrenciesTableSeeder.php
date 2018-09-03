<?php

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->truncate();

        currency()->create([
            'name' => 'Рубль',
            'code' => 'RUB',
            'symbol' => '₽',
            'format' => '1 руб.',
            'exchange_rate' => 1.00000000,
            'active' => 1,
        ]);

        currency()->create([
            'name' => 'Dollar',
            'code' => 'USD',
            'symbol' => '$',
            'format' => '$1,0.00',
            'exchange_rate' => 0.01470000,
            'active' => 1,
        ]);
    }
}
