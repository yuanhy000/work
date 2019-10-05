<?php

namespace App\Listeners\UserRegister;

use App\Events\UserRegister;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class InitUserFriendGroup
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
     * @param UserRegister $event
     * @return void
     */
    public function handle(UserRegister $event)
    {
        Log::info('user register is ok , init user friend group');
        return '123123';
    }
}
