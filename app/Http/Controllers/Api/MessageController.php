<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatEvent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $loggedInUserId = Auth::id();
        $toUserId = $request->get('to_user_id');
        $messages = Message::
        where(function ($query) use ($loggedInUserId, $toUserId) {
            $query->where('user_id', '=', $loggedInUserId)->where('to_user_id', '=', $toUserId);
        })
            ->orWhere(function ($query) use ($loggedInUserId, $toUserId) {
                $query->where('to_user_id', '=', $loggedInUserId)->where('user_id', '=', $toUserId);
            })
            ->orderBy('created_at', 'DESC')
            ->latest()
            ->paginate(10);
        return response()->json($messages);
    }

    public function getConversationList(): JsonResponse
    {
        $loggedInUserId = Auth::id();
        $conversations = Message::select('users.*') // Select all columns from the users table
        ->selectSub(function ($query) {
            $query->select('content')
                ->from('messages')
                ->whereColumn('user_id', 'users.id')
                ->orWhereColumn('to_user_id', 'users.id')
                ->latest()
                ->limit(1);
        }, 'latest_message') // Select the latest message content
        ->where('user_id', $loggedInUserId)
            ->orWhere('to_user_id', $loggedInUserId)
            ->leftJoin('users', function ($join) use ($loggedInUserId) {
                $join->on('user_id', '=', 'users.id')->orOn('to_user_id', '=', 'users.id');
            })
            ->get()->unique();

        return response()->json($conversations);

    }

    public function send(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'content' => 'required|string', // Add validation as necessary
            'to_user_id' => 'required', // Add validation as necessary
        ]);

        $message = new Message;
        $message->content = $validatedData['content'];
        $message->user_id = Auth::id();
        $message->to_user_id = $validatedData['to_user_id'];
        $message->save();

        event(new ChatEvent($message));

        return response()->json();
    }
}
