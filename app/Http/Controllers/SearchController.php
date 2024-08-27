<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\User;

class SearchController extends Controller
{
    public function search()
    {

        $search = request()->search;

        $users = User::where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhereHas('consultations', function ($query) use ($search) {
                    $query->where('type', 'like', '%' . $search . '%');
                });
        })->paginate(10);

        return new UserCollection($users);
    }
}
