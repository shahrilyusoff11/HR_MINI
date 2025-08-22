<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $departments = [
            ['name' => 'Human Resources', 'description' => 'Handles recruitment, training, and employee relations'],
            ['name' => 'Information Technology', 'description' => 'Manages technology infrastructure and systems'],
            ['name' => 'Finance', 'description' => 'Handles financial planning and accounting'],
            ['name' => 'Marketing', 'description' => 'Responsible for promotion and brand management'],
            ['name' => 'Operations', 'description' => 'Manages daily business activities'],
        ];

        DB::table('departments')->insert($departments);
    }
}