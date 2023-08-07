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
                'name' => 'قضية حديدة',
                'user_id' => 1
            ]
        ];

        foreach ($data as $item) {
            Template::firstOrCreate($item);
        }


        $data = [
            [
                'name' => 'قضية ',
                'description' => 'قضية ',
                'user_id' => 1,
                'template_id' => 1,
            ]
        ];

        foreach ($data as $item) {
            Form::firstOrCreate($item);
        }
    }
}
