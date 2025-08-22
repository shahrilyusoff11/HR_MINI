<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypeSeeder extends Seeder
{
    public function run()
    {
        $leaveTypes = [
            ['name' => 'Annual Leave', 'days_per_year' => 21, 'description' => 'Paid time off work'],
            ['name' => 'Sick Leave', 'days_per_year' => 14, 'description' => 'Leave for health reasons'],
            ['name' => 'Maternity Leave', 'days_per_year' => 90, 'description' => 'Leave for childbirth'],
            ['name' => 'Paternity Leave', 'days_per_year' => 7, 'description' => 'Leave for new fathers'],
            ['name' => 'Unpaid Leave', 'days_per_year' => 30, 'description' => 'Leave without pay'],
        ];

        DB::table('leave_types')->insert($leaveTypes);
    }
}