<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffScheduleSeeder extends Seeder
{
    public function run(): void
    {

        $staffs = DB::table('staff')
            ->join('department_staff', 'staff.id', '=', 'department_staff.staff_id')
            ->join('departments', 'departments.id', '=', 'department_staff.department_id')
            ->select(
                'staff.id as staff_id',
                'departments.branch_id as branch_id'
            )
            ->get();

        foreach ($staffs as $staff) {

            for ($weekday = 1; $weekday <= 5; $weekday++) {

                DB::table('staff_schedules')->updateOrInsert(
                    [
                        'staff_id' => $staff->staff_id,
                        'branch_id' => $staff->branch_id,
                        'weekday' => $weekday,
                        'start_time' => '08:00:00',
                        'end_time' => '12:00:00',
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                DB::table('staff_schedules')->updateOrInsert(
                    [
                        'staff_id' => $staff->staff_id,
                        'branch_id' => $staff->branch_id,
                        'weekday' => $weekday,
                        'start_time' => '13:30:00',
                        'end_time' => '17:30:00',
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
