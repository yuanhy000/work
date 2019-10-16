<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel('Friend.accept.{accept_id}', function ($user, $accept_id) {
    return (int)$user->id === (int)$accept_id;
});

Broadcast::channel('Friend.callback.{request_id}', function ($user, $request_id) {
//    return (int)$user->id === (int)$request_id;
    return true;
});

Broadcast::channel('Chat.accept.{accept_id}', function ($user, $accept_id) {
    return (int)$user->id === (int)$accept_id;
//    return true;
});
