<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Wallet;
use App\Rules\isExpert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    //
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt($validated)) {
            return response()->json(['error' => 'email or password are not correct '], 401);
        }

        $user = User::where('email', $validated['email'])->first();

        return response()->json([
            'data' => new userResource($user),
            'access_token' => $user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);

    }

    public function register(SignupRequest $request)
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'balance' => mt_rand(0, 100000)
        ]);

        $user = User::find($user->id);

        return response()->json([
            'data' => new UserResource($user),
            'access_token' => $user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer',
        ]);

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }

    public function delete()
    {
        User::find(Auth::id())->delete();
        return response()->json(['message' => 'account deleted successfully']);
    }


    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['max:255'],
            'address' => [new isExpert(), 'max:255'],
            'phone_number' => [new isExpert(), 'regex:/^(\+?[0-9\s\-\(\)]*)$/', 'min:10', 'max:15'],
            'photo' => ['max:255'],
            'experience' => [new isExpert(), 'max:1000'],
        ]);

        $request->validate([
            'consultation' => ['array', new isExpert()],
            'consultation.*' => 'exists:consultations,id',
        ]);

        $user = Auth::user();
        $user->update($validated);

        $user->consultations()->sync($request->input('consultation', []));

        return response()->json([
            'data' => $user,
            'message' => 'updated successfully'
        ]);

    }

}
