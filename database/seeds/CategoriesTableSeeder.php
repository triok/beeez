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
        create(Category::class, ['nameEu' => 'Text', 'nameRu' => 'Тексты', 'cat_order' => '4']);
        create(Category::class, ['nameEu' => 'Delivery', 'nameRu' => 'Доставка, перевозки', 'cat_order' => '0']);
        create(Category::class, ['nameEu' => 'Websites', 'nameRu' => 'Разработка сайтов', 'cat_order' => '1']);
        create(Category::class, ['nameEu' => 'Applications', 'nameRu' => 'Мобильные приложения', 'cat_order' => '2']);
        create(Category::class, ['nameEu' => 'Content', 'nameRu' => 'Контент', 'cat_order' => '3']);
        create(Category::class, ['nameEu' => 'Design, architecture', 'nameRu' => 'Дизайн и архитектура', 'cat_order' => '7']);
        create(Category::class, ['nameEu' => 'Promotion', 'nameRu' => 'Продвижение и реклама', 'cat_order' => '5']);
        create(Category::class, ['nameEu' => 'Learning', 'nameRu' => 'Обучение', 'cat_order' => '10']);
        create(Category::class, ['nameEu' => 'Consulting', 'nameRu' => 'Консультации', 'cat_order' => '11']);
        create(Category::class, ['nameEu' => 'Photo, video', 'nameRu' => 'Фото, видео', 'cat_order' => '6']);
        create(Category::class, ['nameEu' => 'Repairs', 'nameRu' => 'Ремонт', 'cat_order' => '8']);
        create(Category::class, ['nameEu' => 'Services and help around the house', 'nameRu' => 'Услуги и помощь по дому', 'cat_order' => '9']);
        create(Category::class, ['nameEu' => 'Other', 'nameRu' => 'Другое', 'cat_order' => '12']);                                      
        create(Category::class, ['nameEu' => 'News, Posting', 'nameRu' => 'Новости, постинг', 'cat_order' => '0', 'parent_id' => '1']);
        create(Category::class, ['nameEu' => 'Copywriting', 'nameRu' => 'Копирайтинг', 'cat_order' => '0', 'parent_id' => '1']);
        create(Category::class, ['nameEu' => 'Rewriting', 'nameRu' => 'Рерайтинг', 'cat_order' => '0', 'parent_id' => '1']);
        create(Category::class, ['nameEu' => 'Topics', 'nameRu' => 'Статьи, обзоры', 'cat_order' => '0', 'parent_id' => '1']);        
        create(Category::class, ['nameEu' => 'Translating', 'nameRu' => 'Переводы', 'cat_order' => '0', 'parent_id' => '1']);
        create(Category::class, ['nameEu' => 'Transcription', 'nameRu' => 'Расшифровка аудио-, видеозаписей', 'cat_order' => '0', 'parent_id' => '1']);
        create(Category::class, ['nameEu' => 'Essay, diplomas', 'nameRu' => 'Рефераты, дипломы', 'cat_order' => '0', 'parent_id' => '1']);                
        create(Category::class, ['nameEu' => 'Other', 'nameRu' => 'Другое', 'cat_order' => '0', 'parent_id' => '1']);        
        create(Category::class, ['nameEu' => 'Walking courier', 'nameRu' => 'Пеший курьер', 'cat_order' => '0', 'parent_id' => '2']);
        create(Category::class, ['nameEu' => 'Car courier', 'nameRu' => 'Курьер на автомобиле', 'cat_order' => '0', 'parent_id' => '2']);
        create(Category::class, ['nameEu' => 'Moving', 'nameRu' => 'Переезды', 'cat_order' => '0', 'parent_id' => '2']);
        create(Category::class, ['nameEu' => 'Passenger transportation', 'nameRu' => 'Перевозка пассажиров', 'cat_order' => '0', 'parent_id' => '2']);
        create(Category::class, ['nameEu' => 'Cargo transportation', 'nameRu' => 'Перевозка грузов', 'cat_order' => '0', 'parent_id' => '2']);
        create(Category::class, ['nameEu' => 'Movers', 'nameRu' => 'Грузчики', 'cat_order' => '0', 'parent_id' => '2']);
        create(Category::class, ['nameEu' => 'Other transportation', 'nameRu' => 'Другие перевозки', 'cat_order' => '0', 'parent_id' => '2']);
        create(Category::class, ['nameEu' => 'WebDesign', 'nameRu' => 'Дизайн сайта', 'cat_order' => '0', 'parent_id' => '3']);
        create(Category::class, ['nameEu' => 'Page layout', 'nameRu' => 'Верстка', 'cat_order' => '0', 'parent_id' => '3']); 
        create(Category::class, ['nameEu' => 'Create, tune modules', 'nameRu' => 'Создание, настройка модулей', 'cat_order' => '0', 'parent_id' => '3']);
        create(Category::class, ['nameEu' => 'Turn-key website', 'nameRu' => 'Сайт под ключ', 'cat_order' => '0', 'parent_id' => '3']);
        create(Category::class, ['nameEu' => 'Fix errors', 'nameRu' => 'Исправление ошибок', 'cat_order' => '0', 'parent_id' => '3']); 
        create(Category::class, ['nameEu' => 'Modification', 'nameRu' => 'Доработка сайта', 'cat_order' => '0', 'parent_id' => '3']);
        create(Category::class, ['nameEu' => 'Other', 'nameRu' => 'Другое', 'cat_order' => '0', 'parent_id' => '3']);
        create(Category::class, ['nameEu' => 'Application design', 'nameRu' => 'Дизайн приложений', 'cat_order' => '0', 'parent_id' => '4']);
        create(Category::class, ['nameEu' => 'Turn-key application', 'nameRu' => 'Приложения под ключ', 'cat_order' => '0', 'parent_id' => '4']);
        create(Category::class, ['nameEu' => 'Modification', 'nameRu' => 'Доработка приложений', 'cat_order' => '0', 'parent_id' => '4']); 
        create(Category::class, ['nameEu' => 'Fix errors', 'nameRu' => 'Исправление ошибок', 'cat_order' => '0', 'parent_id' => '4']);
        create(Category::class, ['nameEu' => 'Game application', 'nameRu' => 'Разработка игр', 'cat_order' => '0', 'parent_id' => '4']);        
        create(Category::class, ['nameEu' => 'Other', 'nameRu' => 'Другое', 'cat_order' => '0', 'parent_id' => '4']);
        create(Category::class, ['nameEu' => 'Text', 'nameRu' => 'Создание текстов', 'cat_order' => '0', 'parent_id' => '5']);
        create(Category::class, ['nameEu' => 'Search and create images', 'nameRu' => 'Поиск и создание изображений', 'cat_order' => '0', 'parent_id' => '5']); 
        create(Category::class, ['nameEu' => 'Image processing', 'nameRu' => 'Обработка изображений', 'cat_order' => '0', 'parent_id' => '5']);
        create(Category::class, ['nameEu' => 'Data entry', 'nameRu' => 'Загрузка данных', 'cat_order' => '0', 'parent_id' => '5']); 
        create(Category::class, ['nameEu' => 'Data moving', 'nameRu' => 'Перенос данных', 'cat_order' => '0', 'parent_id' => '5']);                
        create(Category::class, ['nameEu' => 'Other', 'nameRu' => 'Другое', 'cat_order' => '0', 'parent_id' => '5']);
        create(Category::class, ['nameEu' => 'Animation', 'nameRu' => 'Анимация', 'cat_order' => '0', 'parent_id' => '6']);
        create(Category::class, ['nameEu' => 'Illustration', 'nameRu' => 'Иллюстрации', 'cat_order' => '0', 'parent_id' => '6']);        
        create(Category::class, ['nameEu' => 'Logo', 'nameRu' => 'Логотипы', 'cat_order' => '0', 'parent_id' => '6']);
        create(Category::class, ['nameEu' => '3d Graphics', 'nameRu' => '3d графика', 'cat_order' => '0', 'parent_id' => '6']);
        create(Category::class, ['nameEu' => 'Room design', 'nameRu' => 'Дизайн помещений', 'cat_order' => '0', 'parent_id' => '6']);
        create(Category::class, ['nameEu' => 'Land design', 'nameRu' => 'Ландшафтный дизайн', 'cat_order' => '0', 'parent_id' => '6']);
        create(Category::class, ['nameEu' => 'Architecture', 'nameRu' => 'Архитектура', 'cat_order' => '0', 'parent_id' => '6']);                   
        create(Category::class, ['nameEu' => 'Social network design', 'nameRu' => 'Оформление соц. сетей', 'cat_order' => '0', 'parent_id' => '6']);
        create(Category::class, ['nameEu' => 'Banners', 'nameRu' => 'Создание баннеров', 'cat_order' => '0', 'parent_id' => '6']); 
        create(Category::class, ['nameEu' => 'Presentations', 'nameRu' => 'Презентации', 'cat_order' => '0', 'parent_id' => '6']);                
        create(Category::class, ['nameEu' => 'Other', 'nameRu' => 'Другое', 'cat_order' => '0', 'parent_id' => '6']);
        create(Category::class, ['nameEu' => 'SEO', 'nameRu' => 'SEO-продвижение', 'cat_order' => '0', 'parent_id' => '7']);        
        create(Category::class, ['nameEu' => 'Context ads', 'nameRu' => 'Контекстная реклама', 'cat_order' => '0', 'parent_id' => '7']);
        create(Category::class, ['nameEu' => 'SMM', 'nameRu' => 'SMM', 'cat_order' => '0', 'parent_id' => '7']); 
        create(Category::class, ['nameEu' => 'SMO', 'nameRu' => 'SMO', 'cat_order' => '0', 'parent_id' => '7']);
        create(Category::class, ['nameEu' => 'E-mail marketing', 'nameRu' => 'E-Mail Маркетинг', 'cat_order' => '0', 'parent_id' => '7']); 
        create(Category::class, ['nameEu' => 'Lead Generation', 'nameRu' => 'Генерация лидов', 'cat_order' => '0', 'parent_id' => '7']); 
        create(Category::class, ['nameEu' => 'Analytic', 'nameRu' => 'Аналитика', 'cat_order' => '0', 'parent_id' => '7']); 
        create(Category::class, ['nameEu' => 'CRM systems', 'nameRu' => 'CRM-системы', 'cat_order' => '0', 'parent_id' => '7']);                        
        create(Category::class, ['nameEu' => 'Other ads', 'nameRu' => 'Другая реклама', 'cat_order' => '0', 'parent_id' => '7']);
        create(Category::class, ['nameEu' => 'International languages', 'nameRu' => 'Иностранные языки', 'cat_order' => '0', 'parent_id' => '8']);
        create(Category::class, ['nameEu' => 'Programming languages', 'nameRu' => 'Языки программирования', 'cat_order' => '0', 'parent_id' => '8']); 
        create(Category::class, ['nameEu' => 'Fitness training', 'nameRu' => 'Фитнес тренинг', 'cat_order' => '0', 'parent_id' => '8']); 
        create(Category::class, ['nameEu' => 'Student help', 'nameRu' => 'Помощь студентам', 'cat_order' => '0', 'parent_id' => '8']); 
        create(Category::class, ['nameEu' => 'School help', 'nameRu' => 'Помощь школьникам', 'cat_order' => '0', 'parent_id' => '8']);                        
        create(Category::class, ['nameEu' => 'Other', 'nameRu' => 'Другое', 'cat_order' => '0', 'parent_id' => '8']);
        create(Category::class, ['nameEu' => 'Legal consulting', 'nameRu' => 'Юридическая консультация', 'cat_order' => '0', 'parent_id' => '9']); 
        create(Category::class, ['nameEu' => 'Financial consulting', 'nameRu' => 'Бухгалтерская консультация', 'cat_order' => '0', 'parent_id' => '9']); 
        create(Category::class, ['nameEu' => 'Business consulting', 'nameRu' => 'Бизнес консультация', 'cat_order' => '0', 'parent_id' => '9']); 
        create(Category::class, ['nameEu' => 'Doctor consulting', 'nameRu' => 'Врачебная консультация', 'cat_order' => '0', 'parent_id' => '9']);
        create(Category::class, ['nameEu' => 'Psychology consulting', 'nameRu' => 'Консультация психолога', 'cat_order' => '0', 'parent_id' => '9']);
        create(Category::class, ['nameEu' => 'Stylist consulting', 'nameRu' => 'Консультация стилиста', 'cat_order' => '0', 'parent_id' => '9']);                  
        create(Category::class, ['nameEu' => 'Other', 'nameRu' => 'Другое', 'cat_order' => '0', 'parent_id' => '9']);
        create(Category::class, ['nameEu' => 'Product shooting', 'nameRu' => 'Предметная съемка', 'cat_order' => '0', 'parent_id' => '10']); 
        create(Category::class, ['nameEu' => 'Event shooting', 'nameRu' => 'Съемка на мероприятие', 'cat_order' => '0', 'parent_id' => '10']);
        create(Category::class, ['nameEu' => 'Artistic shooting', 'nameRu' => 'Художественная съемка', 'cat_order' => '0', 'parent_id' => '10']);
        create(Category::class, ['nameEu' => 'Advertising shooting', 'nameRu' => 'Рекламная съемка', 'cat_order' => '0', 'parent_id' => '10']);
        create(Category::class, ['nameEu' => 'Photo, video retouch', 'nameRu' => 'Обработка фото, видео', 'cat_order' => '0', 'parent_id' => '10']);
        create(Category::class, ['nameEu' => 'Other', 'nameRu' => 'Другое', 'cat_order' => '0', 'parent_id' => '10']);
        create(Category::class, ['nameEu' => 'Apartment, home repair', 'nameRu' => 'Ремонт в квартире, доме', 'cat_order' => '0', 'parent_id' => '11']);
        create(Category::class, ['nameEu' => 'Plumbing', 'nameRu' => 'Сантехника', 'cat_order' => '0', 'parent_id' => '11']);
        create(Category::class, ['nameEu' => 'Electrical installation', 'nameRu' => 'Электромонтаж', 'cat_order' => '0', 'parent_id' => '11']);                  
        create(Category::class, ['nameEu' => 'Furniture assembly', 'nameRu' => 'Сборка мебели', 'cat_order' => '0', 'parent_id' => '11']);
        create(Category::class, ['nameEu' => 'One our master', 'nameRu' => 'Мастер на час', 'cat_order' => '0', 'parent_id' => '11']); 
        create(Category::class, ['nameEu' => 'Construction works', 'nameRu' => 'Строительные работы', 'cat_order' => '0', 'parent_id' => '11']);
        create(Category::class, ['nameEu' => 'Gadgets repair', 'nameRu' => 'Ремонт мобильных устройств', 'cat_order' => '0', 'parent_id' => '11']);
        create(Category::class, ['nameEu' => 'Household repair', 'nameRu' => 'Ремонт бытовой техники', 'cat_order' => '0', 'parent_id' => '11']);
        create(Category::class, ['nameEu' => 'Auto repair', 'nameRu' => 'Авторемонт', 'cat_order' => '0', 'parent_id' => '11']);
        create(Category::class, ['nameEu' => 'Other', 'nameRu' => 'Другое', 'cat_order' => '0', 'parent_id' => '11']);
        create(Category::class, ['nameEu' => 'Cleaning', 'nameRu' => 'Услуги уборки', 'cat_order' => '0', 'parent_id' => '12']);
        create(Category::class, ['nameEu' => 'Babysitting service', 'nameRu' => 'Услуги няни', 'cat_order' => '0', 'parent_id' => '12']); 
        create(Category::class, ['nameEu' => 'Cook services', 'nameRu' => 'Услуги повара', 'cat_order' => '0', 'parent_id' => '12']);
        create(Category::class, ['nameEu' => 'Nursing services', 'nameRu' => 'Услуги сиделки', 'cat_order' => '0', 'parent_id' => '12']);
        create(Category::class, ['nameEu' => 'Animal care', 'nameRu' => 'Уход за животными', 'cat_order' => '0', 'parent_id' => '12']);
        create(Category::class, ['nameEu' => 'Security', 'nameRu' => 'Охрана', 'cat_order' => '0', 'parent_id' => '12']);
        create(Category::class, ['nameEu' => 'Other', 'nameRu' => 'Другое', 'cat_order' => '0', 'parent_id' => '12']);                                                                                                                          
    }
}
