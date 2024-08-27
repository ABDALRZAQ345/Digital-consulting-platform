<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeTime extends Model
{
    use HasFactory;


    protected $fillable = ['day_of_week', 'start_time', 'end_time', 'salary', 'booked', 'booked_user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookedUser()
    {
        return $this->belongsTo(User::class, 'booked_user_id');
    }

}
