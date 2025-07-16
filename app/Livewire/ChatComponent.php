<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use App\Events\MessageSent;
use App\Events\UnreadMessagesCount;
use App\Events\UserTyping;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app.chat')]
class ChatComponent extends Component
{

    use WithFileUploads;

    public User $receiver;
    public $user;
    public $msg;
    public $forwardMsg;
    public $chatMessages;
    public $image;
    public $imagePath;

    public function mount()
    {
        $this->user = Auth::user();
        $this->chatMessages = $this->getMessages();
        $this->imagePath = null;
    }

    public function getMessages()
    {
        return Message::where(function ($query) {
            $query->where('sender_id', $this->user->id)
                ->where('receiver_id', $this->receiver->id);
        })
            ->orWhere(function ($query) {
                $query->where('sender_id', $this->receiver->id)
                    ->where('receiver_id', $this->user->id);
            })
            ->get();
    }

    public function sendMessage()
    {

        $this->validate(
            [
                'image' => 'nullable|image|mimes:png,jpg,jpeg,webp',
            ],
            [
                'image.mimes' => 'Image type must be only [png, jpg, jpeg, webp]'
            ]
        );

        if ($this->image) $this->imagePath = $this->image->store('images');

        if ($this->msg || $this->imagePath) {
            $sendMessage = Message::create([
                'sender_id' => $this->user->id,
                'receiver_id' => $this->receiver->id,
                'message' => $this->msg,
                'image' => $this->imagePath,
                'is_read' => false,
            ]);

            // append new message to messages property
            $this->chatMessages[] = $sendMessage;

            // dispatch an event
            broadcast(new MessageSent($sendMessage))->toOthers();

            // dispatch unread messages count event
            $unreadCount = $this->unreadMessagesCount();
            broadcast(new UnreadMessagesCount($this->user->id, $this->receiver->id, $unreadCount))->toOthers();

            // reset input
            $this->reset(['msg', 'image', 'imagePath']);

            // dispatch a message sent event 
            $this->dispatch("message-sent", receiverId: $this->receiver->id);
        }
    }

    public function forward($userId)
    {

        Message::create([
            'sender_id' => $this->user->id,
            'receiver_id' => $userId,
            'message' => $this->forwardMsg->message,
            'image' => $this->forwardMsg->image,
            'is_read' => false,
        ]);

        // dispatch unread messages count event
        $unreadCount = $this->unreadMessagesCount();
        broadcast(new UnreadMessagesCount($this->user->id, $userId, $unreadCount))->toOthers();

        $this->reset('forwardMsg');
    }

    // listen for event 
    public function getListeners()
    {
        return [
            "echo-private:message.{$this->user->id}.{$this->receiver->id},.message.sent" => 'listenMessage'
        ];
    }

    public function unreadMessagesCount()
    {
        return Message::where('sender_id', $this->user->id)
            ->where('receiver_id', $this->receiver->id)
            ->where('is_read', false)
            ->count();
    }

    // get forward message 
    #[On('message-forward')]
    public function getForwardMsg($messageId)
    {
        $this->forwardMsg = Message::findOrFail($messageId);
    }


    // delete message
    public function delete($messageId)
    {
        $message = Message::findOrFail($messageId);
        $message->delete();

        $this->chatMessages = $this->getMessages();
    }


    public function listenMessage($event)
    {
        $newMsg = Message::find($event['id']);
        $this->chatMessages[] = $newMsg;
    }

    public function removeImage()
    {
        $this->reset('image');
    }


    public function userTyping()
    {
        broadcast(new UserTyping($this->receiver->id, $this->user->id))->toOthers();
    }

    public function readAllMessages()
    {
        Message::where('sender_id', $this->receiver->id)
            ->where('receiver_id', $this->user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function render()
    {
        $this->readAllMessages();
        return view('livewire.chat-component');
    }
}
