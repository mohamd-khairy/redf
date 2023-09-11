<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserTypeEnum;
use App\Services\RolesService;
use Illuminate\Database\Seeder;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolesService::CreateRole();
        RolesService::CreateUsersForRoles();

        $root = User::firstOrCreate([
            'name' => 'Root',
            'email' => 'root@wakeb.com',
            'password' => bcrypt(123456),
            'type' => UserTypeEnum::EMPLOYEE,
            'email_verified_at' => now(),
            'department_id' => 1,
        ]);

        $root->syncRoles(['admin']);
    }
}
