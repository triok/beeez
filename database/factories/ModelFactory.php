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

use App\Models\Jobs\Application;
use App\Models\Jobs\DifficultyLevel;
use Carbon\Carbon;
use App\User;
use App\Models\Jobs\Job;

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
        'user_id'             => create(\App\User::class)->id,
        'price'               => $faker->randomNumber(2),
        'difficulty_level_id' => create(DifficultyLevel::class)->id,
        'time_for_work'       => random_int(1, 3),
        'status'              => $faker->randomElement(array_values(config('enums.jobs.statuses'))),
    ];
});
$factory->define(App\Models\Jobs\Category::class, function (Faker\Generator $faker) {
    return [
        'name'      => $faker->sentence,
        'desc'      => $faker->sentence,
        'cat_order' => $faker->randomDigitNotNull,
        'parent_id' => null
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
        'description' => $faker->sentence
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