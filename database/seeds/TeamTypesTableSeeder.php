<?php

use App\Models\TeamType;
use Illuminate\Database\Seeder;

class TeamTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Designers', 'Webmasters', 'SEO', 'Content', 'CopyWriters', 'Mobile App', 'Consulting', 'Managment', 'Photo and 3D Graphic', 'Engineering', 'Advertising'];

        foreach ($types as $type) {
            create(TeamType::class, ['name' => $type]);
        }
    }
}