<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Stage;
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
                'order' => 1
            ], [
                'id' => 2,
                'name' => 'مراجعة',
                'key' => 'review',
                'order' => 2
            ], [
                'id' => 3,
                'name' => 'قبول',
                'key' => 'approve',
                'order' => 3
            ], [
                'id' => 4,
                'name' => 'اعادة',
                'key' => 'return',
                'order' => 4
            ], [
                'id' => 5,
                'name' => 'رفض',
                'key' => 'reject',
                'order' => 5
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
            foreach (['create', 'read', 'update', 'delete'] as $key => $value) {
                $permissions[] = Permission::firstOrCreate([
                    'name' => $value . '-' . $stage->key,
                    'display_name' => strtoupper($value) . ' ' . strtolower($stage->key),
                    'guard_name' => 'web',
                    'group' => 'Stage',
                ])->name;
            }

            $role->givePermissionTo($permissions);
        }
    }
}
