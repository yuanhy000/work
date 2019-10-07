<?php

namespace App\Providers;

use App\Events\AddFriend;
use App\Events\FriendCallback;
use App\Events\UserLogin;
use App\Events\UserLogout;
use App\Events\UserRegister;
use App\Listeners\AddFriend\BroadcastAddFriend;
use App\Listeners\AddFriend\CreateAddFriendNotification;
use App\Listeners\FriendCallback\BroadcastFriendCallback;
use App\Listeners\FriendCallback\ChangeAddFriendNotificationStatus;
use App\Listeners\FriendCallback\CreateFriend;
use App\Listeners\FriendCallback\CreateFriendCallbackNotification;
use App\Listeners\UserLogin\DelayOffline;
use App\Listeners\UserLogin\OnlineUser;
use App\Listeners\UserLogout\OfflineUser;
use App\Listeners\UserRegister\InitUserFriendGroup;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
//        Registered::class => [
//            SendEmailVerificationNotification::class,
//        ],
        UserRegister::class => [
            InitUserFriendGroup::class
        ],
        UserLogin::class => [
            OnlineUser::class,
        ],
        UserLogout::class => [
            OfflineUser::class
        ],
        AddFriend::class => [
            CreateAddFriendNotification::class,
        ],
        FriendCallback::class => [
            ChangeAddFriendNotificationStatus::class,
            CreateFriend::class,
            CreateFriendCallbackNotification::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
