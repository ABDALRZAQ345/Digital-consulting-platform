<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'rater_id',
        'expert_id',
        'rate'
    ];

}
