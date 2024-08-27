<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class isExpert implements Rule
{
    public function passes($attribute, $value)
    {
        $user = Auth::user();

// Check if the user is authenticated and is an expert
        return $user && $user->role === 'expert';
    }

    public function message()
    {
        return 'You must be an expert or admin  to perform this action.';
    }
}
