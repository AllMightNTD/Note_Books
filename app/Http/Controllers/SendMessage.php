<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SendMessage extends Controller
{
    //
    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        $user = auth('api')->user();

        broadcast(new MessageSent($user, $message))->toOthers();

        return response()->json(['status' => 'Message Sent!']);
    }
}
