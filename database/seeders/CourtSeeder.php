<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\Department;
use App\Enums\CourtTypeEnum;
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
                'name' => CourtTypeEnum::FIRST_DEGREE,
            ],
            [
                'id' => 2,
                'name' => CourtTypeEnum::APEAL,
            ],
            [
                'id' => 3,
                'name' => CourtTypeEnum::SUPREME,
            ],
            [
                'id' => 4,
                'name' => CourtTypeEnum::IMPLEMENTATION,
            ],
        ];

        foreach ($data as $item) {
            Court::firstOrCreate($item);
        }
    }
}
