<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\RolesService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolesService::CreateRole();

    }
}
