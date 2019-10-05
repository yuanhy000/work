<?php

namespace App\Listeners\FriendCallback;

use App\Events\FriendCallback;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateFriendCallbackNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  FriendCallback  $event
     * @return void
     */
    public function handle(FriendCallback $event)
    {
        //
    }
}
