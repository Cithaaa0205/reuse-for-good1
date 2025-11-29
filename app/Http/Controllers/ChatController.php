<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Menampilkan daftar semua percakapan (inbox).
     */
    public function index()
    {
        $myId = Auth::id();

        // 1. Ambil ID semua orang yang berinteraksi dengan saya
        $receivedFrom = Message::where('receiver_id', $myId)->pluck('sender_id');
        $sentTo       = Message::where('sender_id', $myId)->pluck('receiver_id');

        $userIds = $receivedFrom->merge($sentTo)
            ->unique()
            ->reject(function ($id) use ($myId) {
                return $id == $myId;
            });

        // 2. Siapkan data percakapan
        $conversations = [];
        foreach ($userIds as $userId) {
            $user = User::find($userId);

            // Ambil pesan terakhir
            $lastMessage = Message::where(function ($q) use ($myId, $userId) {
                                    $q->where('sender_id', $myId)
                                      ->where('receiver_id', $userId);
                                 })
                                 ->orWhere(function ($q) use ($myId, $userId) {
                                    $q->where('sender_id', $userId)
                                      ->where('receiver_id', $myId);
                                 })
                                 ->orderBy('created_at', 'desc')
                                 ->first();

            if ($user && $lastMessage) {
                $conversations[] = [
                    'user'        => $user,
                    'lastMessage' => $lastMessage,
                ];
            }
        }

        // Urutkan berdasarkan pesan terbaru
        usort($conversations, function ($a, $b) {
            return $b['lastMessage']->created_at <=> $a['lastMessage']->created_at;
        });

        return view('chat.index', compact('conversations'));
    }

    /**
     * Menampilkan ruang obrolan dengan user tertentu.
     */
    public function show(User $user)
    {
        $otherUser = $user;
        $myId      = Auth::id();

        // Ambil semua pesan antara saya dan user lain
        $messages = Message::where(function ($query) use ($myId, $otherUser) {
                                $query->where('sender_id', $myId)
                                      ->where('receiver_id', $otherUser->id);
                            })
                            ->orWhere(function ($query) use ($myId, $otherUser) {
                                $query->where('sender_id', $otherUser->id)
                                      ->where('receiver_id', $myId);
                            })
                            ->orderBy('created_at', 'asc')
                            ->get();

        // Tandai pesan dari user lain sebagai "sudah dibaca"
        Message::where('sender_id', $otherUser->id)
               ->where('receiver_id', $myId)
               ->whereNull('read_at')
               ->update(['read_at' => now()]);

        return view('chat.show', compact('otherUser', 'messages'));
    }

    /**
     * Menyimpan pesan baru ke database.
     */
    public function store(Request $request, User $user)
    {
        $otherUser = $user;

        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $otherUser->id,
            'message'     => $request->message,
        ]);

        // Kembali ke halaman chat yang sama
        return redirect()->route('chat.show', $otherUser->id);
    }
}
