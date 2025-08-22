<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            DepartmentSeeder::class,
            LeaveTypeSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}