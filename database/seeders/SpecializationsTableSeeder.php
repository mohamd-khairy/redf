<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SpecializationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specializationsData = [
            ['name' => 'الجزائية'],
            ['name' => 'الحقوقية'],
            ['name' => 'الأحوال الشخصية'],
            ['name' => 'التجارية'],
            ['name' => 'العمالية'],
            ['name' => 'الإدارية'],
        ];

        foreach ($specializationsData as $data) {
            Specialization::create($data);
        }
    }
}
