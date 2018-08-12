<?php

use Illuminate\Database\Seeder;
use App\Models\Jobs\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        create(Category::class, ['nameEu' => 'Rewriting', 'nameRu' => 'Тексты', 'cat_order' => '0']);
        create(Category::class, ['nameEu' => 'Graphics', 'nameRu' => 'Графика', 'cat_order' => '0']);
        create(Category::class, ['nameEu' => 'Design', 'nameRu' => 'Дизайн', 'cat_order' => '0']);
        create(Category::class, ['nameEu' => 'Programming', 'nameRu' => 'Программирование', 'cat_order' => '0']);
        create(Category::class, ['nameEu' => 'Content', 'nameRu' => 'Контент', 'cat_order' => '0']);
        create(Category::class, ['nameEu' => 'SEO', 'nameRu' => 'Сео-продвижение', 'cat_order' => '0']);
        create(Category::class, ['nameEu' => 'Child 1', 'nameRu' => 'Подкатегория 1', 'cat_order' => '0', 'parent_id' => '1']);
        create(Category::class, ['nameEu' => 'Child 2', 'nameRu' => 'Подкатегория 2', 'cat_order' => '0', 'parent_id' => '1']);
        create(Category::class, ['nameEu' => 'Child 3', 'nameRu' => 'Подкатегория 3', 'cat_order' => '0', 'parent_id' => '2']);
        create(Category::class, ['nameEu' => 'Child 4', 'nameRu' => 'Подкатегория 4', 'cat_order' => '0', 'parent_id' => '2']);
        create(Category::class, ['nameEu' => 'Child 5', 'nameRu' => 'Подкатегория 5', 'cat_order' => '0', 'parent_id' => '2']);
        create(Category::class, ['nameEu' => 'Child 6', 'nameRu' => 'Подкатегория 6', 'cat_order' => '0', 'parent_id' => '3']);                        
    }
}
