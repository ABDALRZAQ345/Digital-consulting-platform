<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        return response()->json([
            'favorites' => Auth::user()->favorites
        ]);
    }

    public function store(User $user)
    {
        if (\Auth::id() == $user->id) {
            return response()->json(['error' => 'You can\'t favorite yourself']);
        }
        if ($user->role != 'expert') {
            return response()->json(['error' => 'You can\'t favorite non expert users']);
        }
        $exists = Auth::user()->favorites()->where('favorite_user_id', $user->id)->exists();
        if ($exists) {
            Auth::user()->favorites()->detach($user->id);
        } else {
            Auth::user()->favorites()->syncWithoutDetaching($user->id);
        }
        return response()->json([
            'favorites' => Auth::user()->favorites()->get(),
        ]);
    }
    //
}
