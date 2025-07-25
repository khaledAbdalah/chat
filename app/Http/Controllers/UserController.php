<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => User::where('id', '!=', Auth::id())->get(),
        ]);
    }
}
