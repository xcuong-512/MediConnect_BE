<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Department;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\DepartmentResource;
use Illuminate\Http\Request;
use App\Services\AppointmentService;

class PublicController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function getDepartments()
    {
        $departments = Department::all();
        return DepartmentResource::collection($departments);
    }

    public function getDoctors(Request $request)
    {
        $query = Staff::with(['person', 'departments']);

        if ($request->has('department_id')) {
            $query->whereHas('departments', function ($q) use ($request) {
                $q->where('departments.id', $request->department_id);
            });
        }

        $doctors = $query->get();
        return DoctorResource::collection($doctors);
    }

    public function getDoctorDetail($id)
    {
        $doctor = Staff::with(['person', 'departments'])->find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Không tìm thấy bác sĩ'], 404);
        }

        return new DoctorResource($doctor);
    }

    public function getDoctorSlots(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
        ]);

        $date = $request->query('date');

        $slots = $this->appointmentService->generateSlots($id, $date);

        return response()->json([
            'doctor_id' => $id,
            'date' => $date,
            'slots' => $slots
        ]);
    }
}
