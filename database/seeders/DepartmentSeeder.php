<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
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
                'name' => 'الاداره',
                'description' => 'الاداره',
            ],
        ];

        $organizations = [
            [
                'id' => 1,
                'name' => 'التصنيف',
                'description' => 'التصنيف',
            ],
        ];

        foreach ($data as $item) {
            Department::firstOrCreate($item);
        }

        foreach ($organizations as $item) {
            Organization::firstOrCreate($item);
        }
    }
}
