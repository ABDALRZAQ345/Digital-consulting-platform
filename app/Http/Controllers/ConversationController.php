<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationCollection;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use Illuminate\Support\Facades\Gate;

class ConversationController extends Controller
{
    //
    public function index()
    {
        $user = \Auth::user();
        $conversations = $user->conversations();
        return new ConversationCollection($conversations);
    }

    public function show(\Request $request, Conversation $conversation)
    {
        Gate::authorize('view', $conversation);
        $messages = $conversation->messages()->get();
        $another_user_id = $conversation->first_user_id == \Auth::id() ? $conversation->second_user_id : \Auth::id();
        return new ConversationResource($messages);
    }
}
