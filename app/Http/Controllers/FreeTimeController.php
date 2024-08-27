<?php

namespace App\Http\Controllers;

use App\Http\Requests\FreeTimeRequest;
use App\Http\Resources\FreeTimeCollection;
use App\Http\Resources\FreeTimeResource;
use App\Models\FreeTime;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class FreeTimeController extends Controller
{


    //
    public function index(User $user)
    {
        $user = User::where('id', $user->id)->where('role', 'expert')->firstOrFail();

        $freeTimes = $user->freeTimes;

        return new FreeTimeCollection($freeTimes);
    }

    public function show(Request $request, User $user, FreeTime $freeTime)
    {
        $time = $user->freeTimes()->findOrFail($freeTime->id);

        return new FreeTimeResource($time);
    }

    public function store(FreeTimeRequest $request)
    {

        $user = Auth::user();
        Gate::authorize('IsExpert', FreeTime::class);

        $validated = $request->validated();

        $free_time = $user->freeTimes()->create($validated);

        return new FreeTimeResource($free_time);
    }

    public function update(FreeTimeRequest $request, FreeTime $freeTime)
    {
        $user = Auth::user();
        Gate::authorize('IsExpert', FreeTime::class);
        $validated = $request->validated();

        $freeTime = $user->freeTimes()->findOrFail($freeTime->id);
        $freeTime->update($validated);

        return new FreeTimeResource($freeTime);
    }

    public function destroy(FreeTime $freeTime)
    {

        $user = Auth::user();
        Gate::authorize('IsExpert', FreeTime::class);

        $time = $user->freeTimes()->findOrFail($freeTime->id);

        $time->delete();

        return redirect()->route('user.free_times.index', $user);
    }

    public function book(Request $request, User $user, FreeTime $freeTime)
    {

        $time = $user->freeTimes()->findOrFail($freeTime->id);

        if ($time->booked) {

            return $this->handleAlreadyBooked($time);
        }

        if ($time->user_id == Auth::id()) {
            return response()->json([
                'message' => 'that is your Appointment you can not book'
            ]);
        }
        return $this->processBooking($time, $user);
    }

    /// Appointments with my
    public function booked(Request $request, FreeTime $freeTime)
    {
        Gate::authorize('IsExpert', FreeTime::class);
        $user = Auth::user();
        $booked_times = $user->freeTimes()->where('booked', true)->get();
        return new FreeTimeCollection($booked_times);
    }

    /// unbookd
//    public function unbooked(Request $request, FreeTime $freeTime)
//    {
//        Gate::authorize('IsAdmin', FreeTime::class);
//        $user = Auth::user();
//        $booked_times = $user->freeTimes()->where('booked', false)->get();
//        return new FreeTimeCollection($booked_times);
//    }

    // my Appointments
    public function Appointments()
    {
        $user = Auth::user();
        return new FreeTimeCollection($user->bookedFreeTimes);
    }


///
    private function handleAlreadyBooked(FreeTime $time)
    {

        if ($time->booked_user_id == Auth::id()) {
            return response()->json([
                'message' => 'you already booked'
            ]);
        } else {
            return response()->json([
                'message' => 'that Appointment already booked from another user'
            ]);
        }

    }

    ///
    private function processBooking(FreeTime $time, User $user)
    {
        $booked_user = Auth::user();
        $booked_user_wallet = Wallet::where('user_id', $booked_user->id)->first();
        $user_wallet = Wallet::where('user_id', $user->id)->first();

        if (!$booked_user_wallet->Canwithdraw($time->salary)) {

            return response()->json([
                'message' => 'You cannot book an Appointment now enough money to book'
            ]);

        }

        $time->booked_user_id = $booked_user->id;
        $time->booked = true;

        $user_wallet->add($time->salary);
        $booked_user_wallet->withdraw($time->salary);

        $time->save();

        return new FreeTimeResource($time);
    }


}
