<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function Change_role(Request $request, User $user)
    {
        \Gate::authorize('IsAdmin');

        if ($user->role != 'admin') {

            $request->validate([
                'role' => 'required|in:admin,user,expert'
            ]);

            $user->role = $request->role;
            $user->save();

            return new UserResource($user);
        }
        return new UserResource($user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function delete_user(Request $request, User $user)
    {
        \Gate::authorize('IsAdmin');
        if ($user->role != 'admin') {
            $user->delete();
            return response()->json(['message' => 'account deleted successfully']);
        }
        return response()->json(['message' => 'admin account cant be deleted ']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
