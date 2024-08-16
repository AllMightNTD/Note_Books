<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\PusherBroadCast;
use App\Models\Message;

class PusherController extends Controller
{
    // Lấy danh sách tất cả tin nhắn
    public function index()
    {
        // Lấy tất cả tin nhắn từ cơ sở dữ liệu và trả về dạng JSON
        $messages = Message::with('user')->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    // Gửi tin nhắn
    public function broadcast(Request $request)
    {
        // Validate đầu vào
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        // Lưu tin nhắn vào cơ sở dữ liệu
        $message = Message::create([
            'user_id' => auth('api')->user()->id, // Lấy ID người dùng hiện tại
            'message' => $request->get('message'),
            'restaurant_id' => 10
        ]);

        // Phát sóng tin nhắn đến những người khác
        broadcast(new PusherBroadCast($message->message))->toOthers();

        // Trả về phản hồi
        return response()->json(['status' => 'Message broadcasted and saved successfully!', 'message' => $message]);
    }

    // Hàm này không cần thiết nữa vì chúng ta đã hiển thị tin nhắn trong hàm index
    public function receive()
    {
        return response()->json(['status' => 'This method is not used anymore.']);
    }
}
