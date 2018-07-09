<?php

use App\Models\Social;
use Illuminate\Database\Seeder;

class SocialTableSeeder extends Seeder
{
    public function run()
    {
        create(Social::class, ['title' => 'Facebook', 'slug' => 'facebook']);
        create(Social::class, ['title' => 'Instagram', 'slug' => 'instagram']);
        create(Social::class, ['title' => 'LinkedIn', 'slug' => 'linkedin']);
    }
}
