<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'القضايا',
                'user_id' => 1
            ],
            [
                'id' => 2,
                'name' => 'الاستشارات القانونية',
                'user_id' => 1
            ],
            [
                'id' => 3,
                'name' => 'المراجعة والتدقيق',
                'user_id' => 1
            ],

        ];

        foreach ($data as $item) {
            Template::firstOrCreate($item);
        }
    }
}
