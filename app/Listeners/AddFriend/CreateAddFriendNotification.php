<?php

namespace App\Listeners\AddFriend;

use App\Events\AddFriend;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateAddFriendNotification
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
     * @param  AddFriend  $event
     * @return void
     */
    public function handle(AddFriend $event)
    {
        //
    }
}
