<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int)$user->id === (int)$id;
});

Broadcast::channel('Friend.accept.{accept_id}', function ($user, $accept_id) {
    return (int)$user->id === (int)$accept_id;
});

