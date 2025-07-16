<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('message.{receiverId}.{senderId}', function (User $user, $receiverId, $senderId) {
    return (int) $user->id == (int) $receiverId;
});

Broadcast::channel('typing.{receiverId}.{userId}', function (User $user, $receiverId, $userId) {
    return (int) $user->id == (int)$receiverId;
});

Broadcast::channel('unread-messages-count.{receiverId}', function (User $user, $receiverId) {
    return (int) $user->id == (int) $receiverId;
});
