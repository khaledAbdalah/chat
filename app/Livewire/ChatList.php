<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class ChatList extends Component
{
    public $user;
    public $search;


    public function mount()
    {
        $this->user = Auth::user();
    }

    public function getListeners()
    {
        return [
            "echo-private:unread-messages-count.{$this->user->id},UnreadMessagesCount" => 'updateUnreadCount'
        ];
    }

    public function updateUnreadCount()
    {
        $this->render();
    }

    // #[On('message-forwarded')]
    // public function handleMessageForwarded()
    // {
    //     $this->render();
    // }

    public function render()
    {
        $contacts = User::where('id', '!=', Auth::id())
            ->where(function ($query) {
                $query->whereHas('sentMessages', function ($subQuery) {
                    $subQuery->where('receiver_id', Auth::id());
                })
                    ->orWhereHas('receivedMessages', function ($subQuery) {
                        $subQuery->where('sender_id', Auth::id());
                    });
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withCount(['sentMessages as unread_count' => function ($query) {
                $query->where('receiver_id', Auth::id())
                    ->where('is_read', false);
            }])
            ->get()
            ->map(function ($contact) {
                // Add lastMessage to each contact
                $contact->lastMessage = Message::where(function ($query) use ($contact) {
                    $query->where('sender_id', Auth::id())
                        ->where('receiver_id', $contact->id);
                })
                    ->orWhere(function ($query) use ($contact) {
                        $query->where('sender_id', $contact->id)
                            ->where('receiver_id', Auth::id());
                    })
                    ->latest()
                    ->first();

                return $contact;
            })
            ->sortByDesc(function ($contact) {
                return $contact->lastMessage ? $contact->lastMessage->created_at : $contact->created_at;
            });

        return view('livewire.chat-list', compact('contacts'));
    }
}
