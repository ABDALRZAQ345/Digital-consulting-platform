<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    use HasFactory, Notifiable, HasApiTokens;

    const ROLE_ADMIN = 'admin';
    const ROLE_USER = 'user';
    const ROLE_EXPERT = 'expert';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'photo',
        'phone_number',
        'address',
        'sum_of_rates',
        'evaluators'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $guarded = [
        'id',
        'created_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function freeTimes(): HasMany
    {
        return $this->hasMany(FreeTime::class);
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isUser()
    {
        return $this->role === self::ROLE_USER;
    }

    public function isExpert()
    {
        return $this->role === self::ROLE_EXPERT;
    }

    public function consultations()
    {
        return $this->belongsToMany(Consultation::class, user_consultation::class);
    }

    public function bookedFreeTimes(): HasMany
    {
        return $this->hasMany(FreeTime::class, 'booked_user_id');
    }

    public function conversations()
    {
        return Conversation::where('first_user_id', \Auth::id())->orWhere('second_user_id', \Auth::id())->get();
    }

    public function messages()
    {
        $messages = Message::where('sender_id', \Auth::id())->orWhere('receiver_id', \Auth::id())->get();
        return $messages;
    }

    public function favorites()
    {
        return $this->belongsToMany(User::class, Favorite::class, 'user_id', 'favorite_user_id');
    }
}
