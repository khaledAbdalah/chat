<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use App\Events\MessageSent;
use App\Events\UserTyping;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app.chat')]
class ChatComponent extends Component
{

    public User $receiver;
    public $user;
    public $message;
    public $messages;

    public function mount()
    {
        $this->user = Auth::user();
        $this->messages = $this->getMessages();
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
            })->get();
    }

    public function sendMessage()
    {

        $sendMessage = $this->user->sendMessages()->create([
            'receiver_id' => $this->receiver->id,
            'message' => $this->message,
        ]);

        // append new message to messages property
        $this->messages[] = $sendMessage;

        // dispatch an event
        broadcast(new MessageSent($sendMessage))->toOthers();

        // reset input
        $this->reset('message');

        // dispatch a message sent event to update scroll to bottom
        $this->dispatch('message-sent');
    }

    // listen for event 
    public function getListeners()
    {
        return [
            "echo-private:message.{$this->user->id},.message.sent" => 'listenMessage'
        ];
    }


    public function listenMessage($event)
    {
        $newMsg = new Message($event);
        $newMsg->exists = true;
        $newMsg->setConnection(config('database.default'));

        $this->messages[] = $newMsg;
    }


    public function userTyping()
    {
        broadcast(new UserTyping($this->receiver->id, $this->user->id))->toOthers();
    }

    public function render()
    {
        return view('livewire.chat-component');
    }
}
