<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatEvent;
use App\Events\DirectMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $loggedInUserId = Auth::id();
        $keyword = $request->get('keyword');
        $users = User::where('id', '!=', $loggedInUserId)
            ->where(function ($query) use ($keyword) {
                if ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%')->orWhere('email', 'like', '%' . $keyword . '%');
                }
            })
            ->get();
        return response()->json($users);
    }
}
