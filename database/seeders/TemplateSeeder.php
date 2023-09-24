<?php

namespace Database\Seeders;

use App\Enums\FormEnum;
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
                'id' => FormEnum::CASE,
                'name' => 'القضايا',
                'user_id' => 1,
                'icon' => 'mdi-scale-balance',
            ],
            [
                'id' => FormEnum::LEGAL_ADVICE,
                'name' => 'الاستشارات القانونية',
                'user_id' => 1,
                'icon' => 'mdi-scale-balance',
            ],
            // [
            //     'id' => FormEnum::LEGAL_REVIEW,
            //     'name' => 'المراجعة والتدقيق',
            //     'user_id' => 1,
            //     'icon' => 'mdi-scale-balance',
            // ],

        ];

        foreach ($data as $item) {
            Template::firstOrCreate($item);
        }
    }
}
