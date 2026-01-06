<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Staff;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:staff,id',
            'date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'reason' => 'nullable|string'
        ]);

        $user = Auth::user();
        $patient = Patient::where('person_id', $user->person->id)->first();

        if (!$patient) {
            return response()->json(['message' => 'Tài khoản này chưa có hồ sơ bệnh nhân.'], 400);
        }

        $fullTime = $request->date . ' ' . $request->time;

        $exists = Appointment::where('staff_id', $request->doctor_id)
            ->where('scheduled_at', $fullTime)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Khung giờ này đã có người đặt, vui lòng chọn giờ khác.'], 409);
        }

        $doctor = Staff::find($request->doctor_id);
        $branchId = 1;

        $appointment = Appointment::create([
            'patient_id' => $patient->id,
            'staff_id' => $request->doctor_id,
            'branch_id' => $branchId,
            'scheduled_at' => $fullTime,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Đặt lịch thành công!',
            'appointment_id' => $appointment->id
        ], 201);
    }

    public function getMyAppointments(Request $request)
    {
        $user = Auth::user();

        $patient = Patient::where('person_id', $user->person->id)->first();

        if (!$patient) {
            return response()->json(['message' => 'Không tìm thấy hồ sơ bệnh nhân'], 404);
        }

        $appointments = Appointment::with(['doctor.person', 'branch'])
            ->where('patient_id', $patient->id)
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return response()->json($appointments);
    }

    public function getDoctorAppointments(Request $request)
    {
        $user = Auth::user();

        $doctor = Staff::where('person_id', $user->person->id)->first();

        if (!$doctor) {
            return response()->json(['message' => 'Bạn không phải là bác sĩ.'], 403);
        }

        $date = $request->input('date', date('Y-m-d'));

        $appointments = Appointment::with(['patient.person'])
            ->where('staff_id', $doctor->id)
            ->whereDate('scheduled_at', $date)
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return response()->json($appointments);
    }

    public function cancel($id)
    {
        $user = Auth::user();
        $patient = Patient::where('person_id', $user->person->id)->first();

        $appointment = Appointment::where('id', $id)
            ->where('patient_id', $patient->id)
            ->first();

        if (!$appointment) {
            return response()->json(['message' => 'Không tìm thấy lịch hẹn hoặc bạn không có quyền.'], 404);
        }

        if ($appointment->status != 'pending') {
            return response()->json(['message' => 'Chỉ có thể hủy lịch khi đang chờ xác nhận.'], 400);
        }

        $appointment->status = 'cancelled';
        $appointment->save();

        return response()->json(['message' => 'Đã hủy lịch hẹn thành công.']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled'
        ]);

        $user = Auth::user();
        $doctor = Staff::where('person_id', $user->person->id)->first();

        if (!$doctor) {
            return response()->json(['message' => 'Quyền truy cập bị từ chối.'], 403);
        }

        $appointment = Appointment::where('id', $id)
            ->where('staff_id', $doctor->id)
            ->first();

        if (!$appointment) {
            return response()->json(['message' => 'Không tìm thấy lịch hẹn.'], 404);
        }

        $appointment->status = $request->status;
        $appointment->save();

        return response()->json(['message' => 'Cập nhật trạng thái thành công.', 'status' => $appointment->status]);
    }
}
