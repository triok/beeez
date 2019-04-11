<?php

use Illuminate\Database\Seeder;

class UserSpecialitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            create(Page::class, ['name' => 'Административный персонал']);
            create(Page::class, ['name' => 'Бухгалтерия']);
            create(Page::class, ['name' => 'Доставка']);
            create(Page::class, ['name' => 'Медицина']);
            create(Page::class, ['name' => 'Продажи']);
            create(Page::class, ['name' => 'IT, телеком']);
            create(Page::class, ['name' => 'Реклама/продвижение']);
            create(Page::class, ['name' => 'Ремонт']);
            create(Page::class, ['name' => 'Строительство']);
            create(Page::class, ['name' => 'Транспорт']);
            create(Page::class, ['name' => 'Юриспруденция']);
    }
}
