<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'balance', 'user_id'
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function Canwithdraw($amount)
    {
        return $this->balance >= $amount;
    }

    public function add($amount)
    {
        $this->balance += $amount;
        $this->save();
    }

    public function withdraw($amount)
    {
        $this->balance -= $amount;
        $this->save();
    }

}
