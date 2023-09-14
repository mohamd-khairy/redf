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
        $stage1 = Stage::firstOrCreate([
            'name' => 'بناء',
            'key' => 'applied',
        ]);

        $appliedRole = Role::firstOrCreate([
            'name' => 'applied',
            'guard_name' => 'web',
            'display_name' => 'بناء',
        ]);

        $createStage1Permission = Permission::firstOrCreate([
            'name' => 'create-applied',
            'guard_name' => 'web',
            'display_name' => 'Create Applied',
            'group' => 'Stage',
        ]);

        $readStage1Permission = Permission::firstOrCreate([
            'name' => 'read-applied',
            'guard_name' => 'web',
            'display_name' => 'Read Applied',
            'group' => 'Stage',
        ]);

        $updateStage1Permission = Permission::firstOrCreate([
            'name' => 'update-applied',
            'guard_name' => 'web',
            'display_name' => 'Update Applied',
            'group' => 'Stage',
        ]);

        $deleteStage1Permission = Permission::firstOrCreate([
            'name' => 'delete-applied',
            'guard_name' => 'web',
            'display_name' => 'Delete Applied',
            'group' => 'Stage',
        ]);

        $appliedRole->givePermissionTo([$createStage1Permission,$readStage1Permission,$updateStage1Permission,$deleteStage1Permission]);

        $stage2 = Stage::firstOrCreate([
            'name' => 'مراجعة',
            'key' => 'review',
        ]);

        $reviewRole = Role::firstOrCreate([
            'name' => 'review',
            'guard_name' => 'web',
            'display_name' => 'مراجعة',
        ]);

        $createStage2Permission = Permission::firstOrCreate([
            'name' => 'create-review',
            'guard_name' => 'web',
            'display_name' => 'Create Review',
            'group' => 'Stage',
        ]);

        $readStage2Permission = Permission::firstOrCreate([
            'name' => 'read-review',
            'guard_name' => 'web',
            'display_name' => 'Read Review',
            'group' => 'Stage',
        ]);

        $updateStage2Permission = Permission::firstOrCreate([
            'name' => 'update-review',
            'guard_name' => 'web',
            'display_name' => 'Update Review',
            'group' => 'Stage',
        ]);

        $deleteStage2Permission = Permission::firstOrCreate([
            'name' => 'delete-review',
            'guard_name' => 'web',
            'display_name' => 'Delete Review',
            'group' => 'Stage',
        ]);

        $reviewRole->givePermissionTo([$createStage2Permission,$readStage2Permission,$updateStage2Permission,$deleteStage2Permission]);

        $stage3 = Stage::firstOrCreate([
            'name' => 'قبول',
            'key' => 'approve',
        ]);

        $approveRole = Role::firstOrCreate([
            'name' => 'approve',
            'guard_name' => 'web',
            'display_name' => 'قبول',
        ]);

        $createStage3Permission = Permission::firstOrCreate([
            'name' => 'create-approve',
            'guard_name' => 'web',
            'display_name' => 'Create Approve',
            'group' => 'Stage',
        ]);

        $readStage3Permission = Permission::firstOrCreate([
            'name' => 'read-approve',
            'guard_name' => 'web',
            'display_name' => 'Read Approve',
            'group' => 'Stage',
        ]);

        $updateStage3Permission = Permission::firstOrCreate([
            'name' => 'update-approve',
            'guard_name' => 'web',
            'display_name' => 'Update Approve',
            'group' => 'Stage',
        ]);

        $deleteStage3Permission = Permission::firstOrCreate([
            'name' => 'delete-approve',
            'guard_name' => 'web',
            'display_name' => 'Delete Approve',
            'group' => 'Stage',
        ]);

        $approveRole->givePermissionTo([$createStage3Permission,$readStage3Permission,$updateStage3Permission,$deleteStage3Permission]);


        $stage4 = Stage::firstOrCreate([
            'name' => 'اعادة',
            'key' => 'return',
        ]);

        $returnRole = Role::firstOrCreate([
            'name' => 'return',
            'guard_name' => 'web',
            'display_name' => 'اعادة',
        ]);

        $createStage4Permission = Permission::firstOrCreate([
            'name' => 'create-return',
            'guard_name' => 'web',
            'display_name' => 'Create Return',
            'group' => 'Stage',
        ]);

        $readStage4Permission = Permission::firstOrCreate([
            'name' => 'read-return',
            'guard_name' => 'web',
            'display_name' => 'Read Return',
            'group' => 'Stage',
        ]);

        $updateStage4Permission = Permission::firstOrCreate([
            'name' => 'update-return',
            'guard_name' => 'web',
            'display_name' => 'Update Return',
            'group' => 'Stage',
        ]);

        $deleteStage4Permission = Permission::firstOrCreate([
            'name' => 'delete-return',
            'guard_name' => 'web',
            'display_name' => 'Delete Return',
            'group' => 'Stage',
        ]);

        $returnRole->givePermissionTo([$createStage4Permission,$readStage4Permission,$updateStage4Permission,$deleteStage4Permission]);


        $stage5 = Stage::firstOrCreate([
            'name' => 'رفض',
            'key' => 'reject',
        ]);

        $rejectRole = Role::firstOrCreate([
            'name' => 'reject',
            'guard_name' => 'web',
            'display_name' => 'رفض',
        ]);

        $createStage5Permission = Permission::firstOrCreate([
            'name' => 'create-reject',
            'guard_name' => 'web',
            'display_name' => 'Create Reject',
            'group' => 'Stage',
        ]);

        $readStage5Permission = Permission::firstOrCreate([
            'name' => 'read-reject',
            'guard_name' => 'web',
            'display_name' => 'Read Reject',
            'group' => 'Stage',
        ]);

        $updateStage5Permission = Permission::firstOrCreate([
            'name' => 'update-reject',
            'guard_name' => 'web',
            'display_name' => 'Update Reject',
            'group' => 'Stage',
        ]);

        $deleteStage5Permission = Permission::firstOrCreate([
            'name' => 'delete-reject',
            'guard_name' => 'web',
            'display_name' => 'Delete Reject',
            'group' => 'Stage',
        ]);

        $rejectRole->givePermissionTo([$createStage5Permission,$readStage5Permission,$updateStage5Permission,$deleteStage5Permission]);

    }
}
