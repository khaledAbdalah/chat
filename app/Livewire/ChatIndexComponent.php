<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class ChatIndexComponent extends Component
{
    public $href;
    public $action;
    public $search;

    public function render()
    {
        $users = User::where('name', 'LIKE', "%$this->search%")->limit(5)->get();
        return view('livewire.chat-index-component', compact('users'));
    }
}
