<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    //


    public function index()
    {
        $user = Auth::user();
        $messages = $user->messages();
        return new MessageCollection($messages);
    }

    public function show(User $user)
    {
        $second_user = $user;
        $conversation = $this->get_conversation(Auth::user(), $second_user);
        $messages = $conversation->messages()->get();
        return new MessageCollection($messages);
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => ['required', 'max:3000']
        ]);

        $sender = Auth::user();
        $receiver = $user;
        $conversation = $this->get_conversation($sender, $receiver);


        $message = Message::create([
            'sender_id' => \Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
            'sending_date' => now(),
            'is_read' => false,
            'conversation_id' => $conversation->id
        ]);

        return new MessageResource($message);
    }

    protected function get_conversation(User $sender, User $receiver)
    {
        $conversation = Conversation::where(function ($query) use ($receiver, $sender) {
            $query->where('first_user_id', $receiver->id)->where('second_user_id', $sender->id);
        })->orWhere(function ($query) use ($receiver, $sender) {
            $query->where('first_user_id', $sender->id)->where('second_user_id', $receiver->id);
        })->first();
        if (!$conversation) {
            $conversation = Conversation::create([
                'first_user_id' => $sender->id,
                'second_user_id' => $receiver->id,
            ]);
        }
        return $conversation;
    }
}
