<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comment;
use App\Models\Jobs\Application;
use App\Models\Jobs\DifficultyLevel;
use App\Models\Modules;
use App\Models\Organization;
use App\Models\Project;
use App\Models\Page;
use App\Models\Team;
use App\Models\TeamType;

use App\Models\Vacancy;
use Carbon\Carbon;
use App\User;
use App\Models\Jobs\Job;
use App\Models\Jobs\JobCategories;
use App\Models\Jobs\Skill;
use App\Models\Jobs\TimeForWork;

$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name'           => $faker->name,
        'username'       => $faker->name,
        'email'          => $faker->unique()->safeEmail,
        'password'       => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Job::class, function (Faker\Generator $faker) {
  return [
        'name'                => $faker->jobTitle,
        'desc'                => $faker->text(200),
        'instructions'        => $faker->sentence,
        'access'              => $faker->text(10),
        'end_date'            => Carbon::now()->addDays(rand(1,5)),
        'user_id'             => User::query()->exists() ? $faker->randomElement(User::query()->pluck('id')->toArray()) : create(User::class)->id,
        'price'               => $faker->randomNumber(2),
        'difficulty_level_id' => create(DifficultyLevel::class)->id,
        'time_for_work'       => random_int(1, 11),
        'status'              => $faker->randomElement(array_values(config('enums.jobs.statuses'))),
        'parent_id'           => random_int(1, 12),
    ];
});

$factory->define(App\Models\Jobs\Category::class, function (Faker\Generator $faker) {
    return [
        'nameEu'    => $faker->sentence,
        'nameRu'    => $faker->sentence,
        'desc'      => $faker->sentence,
        'cat_order' => $faker->randomDigitNotNull,
        'parent_id' => null
    ];
});

$factory->define(JobCategories::class, function (Faker\Generator $faker) {
  return [
        'category_id'         => $faker->randomElement(App\Models\Jobs\Category::pluck('id')->toArray()),
        'job_id'              => $faker->randomElement(Job::pluck('id')->toArray()),
    ];
});

$factory->define(Skill::class, function (Faker\Generator $faker) {
  return [
        'name'         => $faker->sentence,
    ];
});

$factory->define(TimeForWork::class, function (Faker\Generator $faker) {
  return [
        'value'         => $faker->sentence,
    ];
});

$factory->define(App\Models\Social::class, function (Faker\Generator $faker) {
    return [
        'title'      => $faker->sentence,
    ];
});

$factory->define(\App\Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'display_name' => $faker->name,
        'description' => $faker->sentence,
    ];
});

$factory->define(DifficultyLevel::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name
    ];
});

$factory->define(Application::class, function (Faker\Generator $faker) {
    return [
        'user_id' => create(User::class)->id,
        'job_id' => create(Job::class)->id,
        'remarks' => $faker->sentence(10),
        'admin_remarks' => $faker->sentence(10),
        'deadline' => Carbon::now()->addDay(5),
        'job_price' => $faker->randomDigitNotNull(),
        'status' => $faker->randomElement(array_values(config('enums.jobs.statuses'))),
    ];
});

$factory->define(Modules::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->title,
    ];
});

$factory->define(Comment::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->title,
        'commentable_id' => create(Job::class)->id,
        'commentable_type' => Job::class,
        'body' => $faker->text,
        'author_id' => create(User::class)->id,
        'author_type' => User::class,
    ];
});

$factory->define(TeamType::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});


$factory->define(Page::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->title,
        'description' => $faker->text,
    ];
});

$factory->define(Team::class, function (Faker\Generator $faker) {
    $name = $faker->sentence(rand(1, 3));

    return [
        'user_id' => create(User::class)->id,
        'team_type_id' => $faker->randomElement(TeamType::pluck('id')->all()),
        'name' => $name,
        'slug' => str_slug($name),
        'logo' => null,
        'description' => $faker->text,
    ];
});


$factory->define(Organization::class, function (Faker\Generator $faker) {
    $name = $faker->sentence(rand(1, 3));

    return [
        'user_id' => create(User::class)->id,
        'name' => $name,
        'slug' => str_slug($name),
        'logo' => null,
        'description' => $faker->text,
    ];
});

$factory->define(Project::class, function (Faker\Generator $faker) {
    return [
        'user_id' => create(User::class)->id,
        'name' => $faker->sentence(rand(1, 3)),
        'description' => $faker->text,
    ];
});

$factory->define(Organization::class, function (Faker\Generator $faker) {
    $name = $faker->word;

    return [
        'user_id' => create(User::class)->id,
        'name' => $name,
        'slug' => str_slug($name),
        'status' => $faker->randomElement(['approved', 'moderation']),
        'description' => $faker->text,
    ];
});

$factory->define(Vacancy::class, function (Faker\Generator $faker) {
    return [
        'organization_id' => create(Organization::class)->id,
        'name' => $faker->word,
        'specialization' => $faker->randomElement(config('vacancy.specializations')),
        'published_at' => $faker->randomElement([null, Carbon::now()]),
        'total_views' => rand(0, 100),
        'total_responses' => rand(0, 10),
    ];
});

$factory->define(UserSpeciality::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});