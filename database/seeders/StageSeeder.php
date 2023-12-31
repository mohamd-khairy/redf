<?php

namespace Database\Seeders;

use App\Enums\FormEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Stage;
use App\Models\StageForm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stages = [
            [
                'id' => 1,
                'name' => 'بناء',
                'key' => 'applied',
            ], [
                'id' => 2,
                'name' => 'مراجعة',
                'key' => 'review',
            ], [
                'id' => 3,
                'name' => 'قبول',
                'key' => 'approve',
            ], [
                'id' => 4,
                'name' => 'اعادة',
                'key' => 'return',
            ], [
                'id' => 5,
                'name' => 'رفض',
                'key' => 'reject',
            ]
        ];

        foreach ($stages as $key => $stage) {
            $stage = Stage::firstOrCreate($stage);
            $role = Role::firstOrCreate([
                'name' => $stage->key,
                'guard_name' => 'web',
                'display_name' =>  $stage->name,
            ]);

            $permissions = [];
            foreach (['accept', 'return', 'reject'] as $key => $value) {
                $permissions[] = Permission::firstOrCreate([
                    'name' => $value . '-' . $stage->key,
                    'display_name' => strtoupper($value) . ' ' . strtolower($stage->key),
                    'guard_name' => 'web',
                    'group' => $stage->key,
                ])->name;
            }

            $role->givePermissionTo($permissions);

            foreach ([
                FormEnum::DEFENCE_CASE_FORM, FormEnum::CLAIM_CASE_FORM, FormEnum::RESUME_CASE_FORM,
                FormEnum::SOLICITATION_CASE_FORM, FormEnum::OBJECTION_CASE_FORM, FormEnum::IMPLEMENTATION_CASE_FORM
            ] as $key => $value) {
                $stage_form = StageForm::firstOrCreate([
                    'form_id' => $value,
                    'stage_id' => $stage->id,
                    'order' => $key,
                    'active' =>  true,
                ]);
            }
        }
    }
}
