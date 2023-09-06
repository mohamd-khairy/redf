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

        $rolesToCreateUsersFor = [
            'admin' => 'مدير',
            'manager' => 'مدير عام',
            'tasks' => 'المهام',
            'cases_manager' => 'مدير اداره القضايا',
            'litigation' => 'مدير قسم التقاضي',
            'consultant' => 'مستشار',
            'reports' => 'التقارير',
            'settings' => 'الإعدادات',
            'employee' => 'موظف',
            'data_entry' => 'مدخل البيانات',
        ];

        // Call the CreateUsersForRoles method to create users for these roles
        RolesService::CreateUsersForRoles($rolesToCreateUsersFor);

        $root = User::firstOrCreate([
            'name' => 'Root',
            'email' => 'root@wakeb.com',
            'password' => bcrypt(123456),
            'type' => UserTypeEnum::EMPLOYEE,
            'email_verified_at' => now()
        ]);

        $admin = User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@wakeb.com',
            'password' => bcrypt(123456),
            'type' => UserTypeEnum::EMPLOYEE,
            'email_verified_at' => now()
        ]);

        // User::firstOrCreate([
        //     'name' => 'صندوق التنمية العقارية',
        //     'email' => 'my@wakeb.com',
        //     'password' => bcrypt(123456),
        //     'type' => UserTypeEnum::EMPLOYEE,
        //     'email_verified_at' => now(),
        //     'department_id' => 1
        // ])->assignRole('manager');

        $root->syncRoles(['admin']);
        $admin->assignRole('admin');
    }
}
