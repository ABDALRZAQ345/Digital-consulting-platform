<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function rate(Request $request, User $user)
    {
        if (\Auth::id() == $user->id) {
            return response()->json(['error' => 'You can\'t rate yourself']);
        }
        if ($user->role != 'expert') {
            return response()->json(['error' => 'You can\'t rate non expert users']);
        }
        $request->validate([
            'rate' => ['required', 'integer', 'min:1', 'max:5']
        ]);
        $rate = Rate::where('rater_id', Auth::id())->where('expert_id', $user->id)->first();

        if (!$rate) {
            $rate = Rate::create([
                'rater_id' => Auth::id(),
                'expert_id' => $user->id,
                'rate' => $request->rate,
            ]);
            $user->sum_of_rates += $request->rate;
            $user->evaluators++;
            $user->save();

        } else {
            $user->sum_of_rates -= $rate->rate;
            $user->sum_of_rates += $request->rate;
            $rate->rate = $request->rate;
            $rate->save();
            $user->save();
        }

        return response()->json(
            [
                'data' => $user,
                'new rate' => $user->sum_of_rates / $user->evaluators,
            ]
        );

    }


}
