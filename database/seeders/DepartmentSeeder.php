<?php

namespace Database\Seeders;

use App\Models\Department;
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

        foreach ($data as $item) {
            Department::firstOrCreate($item);
        }
    }
}
