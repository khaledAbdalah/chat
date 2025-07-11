<?php

namespace App\Livewire;

use App\Models\Message;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatList extends Component
{
    public $search;

    public function render()
    {
        $contacts = Message::with(['sender', 'receiver'])
            ->where(function ($query) {
                $query->where('sender_id', Auth::id())
                    ->orWhere('receiver_id', Auth::id());
            })
            ->latest('updated_at')
            ->get()
            ->groupBy(fn($msg) =>  $msg->sender_id === Auth::id() ? $msg->receiver_id : $msg->sender_id);
        return view('livewire.chat-list', compact('contacts'));
    }
}
