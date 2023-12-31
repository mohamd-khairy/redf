<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Modules\Report\Database\Seeders\ReportDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
        /**
         * Seed the application's database.
         *
         * @return void
         */
        public function run()
        {
                $this->call(SpecializationsTableSeeder::class);
                $this->call(StatusesTableSeeder::class);
                $this->call(CourtSeeder::class);
                $this->call(DepartmentSeeder::class);
                $this->call(SettingSeeder::class);
                $this->call(UserRolePermissionSeeder::class);
                $this->call(TemplateSeeder::class);
                $this->call(CaseRelatedSeeder::class);
                $this->call(ReportDatabaseSeeder::class);
                $this->call(FormSeeder::class);
                $this->call(StageSeeder::class);
        }
}
