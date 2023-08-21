<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CourtSeeder extends Seeder
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
                'name' => 'الدرجه الأولي',
            ],
            [
                'id' => 2,
                'name' => 'الاستئناف',
            ],
            [
                'id' => 3,
                'name' => 'العليا ',
            ],
            [
                'id' => 4,
                'name' => 'التنفيذ',
            ],
        ];

        foreach ($data as $item) {
            Court::firstOrCreate($item);
        }
    }
}
