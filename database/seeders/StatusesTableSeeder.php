<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StatusesTableSeeder extends Seeder
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
                'key' => 'PENDING',
                'value' => 'Pending',
            ],
            [
                'id' => 2,
                'key' => 'PROCESSING',
                'value' => 'Processing',
            ],
            [
                'id' => 3,
                'key' => 'COMPLETED',
                'value' => 'Completed',
            ],
            [
                'id' => 4,
                'key' => 'CLOSED',
                'value' => 'Closed',
            ],
            [
                'id' => 5,
                'key' => 'ASSIGNED',
                'value' => 'Assigned',
            ],

        ];

        foreach ($data as $item) {
            Status::firstOrCreate($item);
        }
    }
}
