<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorController extends Controller
{

    public function schedules($id, Request $request)
    {
        $date = $request->query('date');

        if (!$date) {
            return response()->json([
                'message' => 'date is required'
            ], 400);
        }

        $weekday = Carbon::parse($date)->dayOfWeekIso;

        $schedules = DB::table('staff_schedules')
            ->where('staff_id', $id)
            ->where('weekday', $weekday)
            ->select('start_time', 'end_time')
            ->get();

        return response()->json($schedules);
    }
}
