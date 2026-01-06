<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\MedicalRecord;
use App\Models\Prescription;

class ConsultationController extends Controller
{
    public function store(Request $request, $appointmentId)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'diagnosis' => 'required|string',
            'treatment_plan' => 'nullable|string',
            'notes' => 'nullable|string',
            'prescriptions' => 'nullable|array',
            'prescriptions.*.medicine_name' => 'required|string',
            'prescriptions.*.dosage' => 'required|string',
            'prescriptions.*.duration' => 'required|string',
        ]);

        $appointment = Appointment::find($appointmentId);
        if (!$appointment) {
            return response()->json(['message' => 'Lịch hẹn không tồn tại'], 404);
        }

        if ($appointment->status === 'completed') {
            return response()->json(['message' => 'Lịch hẹn này đã hoàn thành trước đó'], 400);
        }

        try {
            DB::beginTransaction();

            $encounter = Encounter::create([
                'appointment_id' => $appointment->id,
            ]);

            $medicalRecord = MedicalRecord::create([
                'encounter_id' => $encounter->id,
                'diagnosis' => $request->diagnosis,
                'treatment_plan' => $request->treatment_plan,
                'notes' => $request->notes
            ]);

            if ($request->has('prescriptions')) {
                foreach ($request->prescriptions as $med) {
                    Prescription::create([
                        'medical_record_id' => $medicalRecord->id,
                        'medicine_name' => $med['medicine_name'],
                        'dosage' => $med['dosage'],
                        'duration' => $med['duration'],
                    ]);
                }
            }

            $appointment->status = 'completed';
            $appointment->save();

            DB::commit();

            return response()->json([
                'message' => 'Hoàn thành khám bệnh thành công!',
                'medical_record_id' => $medicalRecord->id
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }
}
