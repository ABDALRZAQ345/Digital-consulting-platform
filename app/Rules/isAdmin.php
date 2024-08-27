<?php

namespace App\Rules;


use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class isAdmin implements Rule
{
    public function passes($attribute, $value)
    {
        $user = Auth::user();

// Check if the user is authenticated and is an admin
        return $user && $user->role === 'admin';
    }

    public function message()
    {
        return 'You must be an admin to perform this action.';
    }
}
