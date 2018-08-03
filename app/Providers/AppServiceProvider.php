<?php

namespace App\Providers;

use App\Models\Jobs\Category;
use App\Models\Jobs\DifficultyLevel;
use App\Models\Jobs\Skill;
use Illuminate\Support\ServiceProvider;
use View;
use Schema;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Schema::defaultStringLength(191);
        /** @var Skill $skills */
        $_skills     = Skill::query()->get();
        /** @var Category $_categories */
        $_categories = Category::query()->orderBy('cat_order')->get();
        /** @var DifficultyLevel $_difficultyLevels */
        $_difficultyLevels = DifficultyLevel::pluck('name', 'id');


        View::share('_skills', $_skills);
        View::share('_categories', $_categories);
        View::share('_difficultyLevels', $_difficultyLevels);
    }

    public function register()
    {
        require_once __DIR__.'/../Http/helpers.php';
    }
}
