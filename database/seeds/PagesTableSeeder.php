<?php

use Illuminate\Database\Seeder;
use App\Models\Page;
use Illuminate\Support\Facades\DB;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            create(Page::class, ['title' => 'О компании', 'description' => 'О компании']);
            create(Page::class, ['title' => 'Помощь', 'description' => 'Помощь']);
            create(Page::class, ['title' => 'Безопасность', 'description' => 'Безопасность']);
            create(Page::class, ['title' => 'Условия', 'description' => 'Условия']);
            create(Page::class, ['title' => 'Контакты', 'description' => 'Контакты']);
            create(Page::class, ['title' => 'Соц. сети', 'description' => 'Соц. сети']);                         
    }
}
