<?php

namespace App\Services;

use App\Models\StaffSchedule;
use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentService
{
    /**
     * @param int $doctorId
     * @param string $date (Format: Y-m-d)
     * @return array
     */
    public function generateSlots($doctorId, $date)
    {

        $carbonDate = Carbon::parse($date);
        $weekday = $carbonDate->dayOfWeek;

        $schedules = StaffSchedule::where('staff_id', $doctorId)
            ->where('weekday', $weekday)
            ->get();

        if ($schedules->isEmpty()) {
            return [];
        }

        $bookedSlots = Appointment::where('staff_id', $doctorId)
            ->whereDate('scheduled_at', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('scheduled_at')
            ->map(function ($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        $slots = [];
        $duration = 30;

        foreach ($schedules as $schedule) {
            $startTime = Carbon::parse($date . ' ' . $schedule->start_time);
            $endTime = Carbon::parse($date . ' ' . $schedule->end_time);

            while ($startTime->lt($endTime)) {
                $timeString = $startTime->format('H:i');

                $isBooked = in_array($timeString, $bookedSlots);

                $isPast = $carbonDate->isToday() && $startTime->lt(Carbon::now());

                $slots[] = [
                    'time' => $timeString,
                    'is_booked' => $isBooked || $isPast,
                ];

                $startTime->addMinutes($duration);
            }
        }

        return $slots;
    }
}
