<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = DB::table('departments')
            ->select('id', 'name')
            ->get();

        return response()->json($departments);
    }

    public function doctors($id)
    {
        $doctors = DB::table('staff')
            ->join('department_staff', 'staff.id', '=', 'department_staff.staff_id')
            ->join('people', 'people.id', '=', 'staff.person_id')
            ->where('department_staff.department_id', $id)
            ->select(
                'staff.id as doctor_id',
                'people.full_name'
            )
            ->get();

        return response()->json($doctors);
    }
}
