<?php

use App\Http\Controllers\UserController;
use App\Livewire\ChatComponent;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => 'hello')->name('home');
Route::redirect('/', 'dashboard');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('users', [UserController::class, 'index'])->name('users.index')
  ->middleware(['auth', 'verified']);

Route::middleware(['auth'])->group(function () {



     // chat routes
    Route::get('chats', fn() => view('chats.index'))->name('chat.index');
    Route::get('chats/{receiver}', ChatComponent::class)->name('chat.show');


    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
