<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Appearance extends Component
{

    public $id;

    public function mount()
    {
        $this->id = Auth::id();
    }

    public function handleMessageSent($event)
    {
        dd($event);
    }


    public function getListeners()
    {
        return [
            'echo-private:chat.' . $this->id . ',.message.sent'=> 'handleMessageSent',
        ];
    }
}
