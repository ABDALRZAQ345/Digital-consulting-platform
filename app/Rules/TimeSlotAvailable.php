<?php

namespace App\Rules;

use App\Models\FreeTime;
use Illuminate\Contracts\Validation\Rule;

class TimeSlotAvailable implements Rule
{
    protected $userId;
    protected $day;

    public function __construct($userId, $day)
    {
        $this->userId = $userId;
        $this->day = $day;
    }

    public function passes($attribute, $value)
    {
        $startTime = request()->input('start_time');
        $endTime = request()->input('end_time');

        return !FreeTime::where('user_id', $this->userId)
            ->where('day_of_week', $this->day)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($query) use ($startTime, $endTime) {
                        $query->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();
    }

    public function message()
    {
        return 'The specified time slot overlaps with an existing time slot.';
    }
}
