<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\Department;
use App\Enums\CourtTypeEnum;
use App\Models\Branch;
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
            'المحكمة الإدارية العليا',
            'محكمة الاستئناف الإدارية بمنطقة الرياض',
            'محكمة الاستئناف الإدارية بمنطقة مكة المكرمة',
            'محكمة الاستئناف الإدارية بالمنطقة الشرقية',
            'محكمة الاستئناف الإدارية بمنطقة عسير',
            'محكمة الاستئناف الإدارية بمنطقة المدينة المنورة',
            'محكمة الاستئناف الإدارية بمنطقة الجوف',
            'محكمة الاستئناف الإدارية بمنطقة القصيم',
            'المحكمة الإدارية بالرياض',
            'المحكمة الإدارية بجدة',
            'المحكمة الإدارية بالدمام',
            'المحكمة الإدارية بأبها',
            'المحكمة الإدارية بالمدينة المنورة',
            'المحكمة الإدارية بسكاكا',
            'المحكمة الإدارية ببريدة',
            'المحكمة الإدارية بالأحساء',
            'المحكمة الإدارية بحائل',
            'المحكمة الإدارية بعرعر',
            'المحكمة الإدارية بمكة المكرمة',
            'المحكمة الإدارية بجازان',
            'المحكمة الإدارية بنجران',
            'المحكمة الإدارية بتبوك',
            'المحكمة الإدارية بالباحة',
            'المحكمة الإدارية الرقميّة بوادي الدواسر',
            'المحكمة الإدارية بحفر الباطن'
        ];

        foreach ($data as $item) {
            Branch::firstOrCreate(['name' => $item]);
        }
    }
}
