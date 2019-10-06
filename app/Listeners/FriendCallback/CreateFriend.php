<?php

namespace App\Listeners\FriendCallback;

use App\Events\FriendCallback;
use App\Exceptions\BaseException;
use App\Friend;
use Exception;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class CreateFriend
{

    public function __construct()
    {
        //
    }

    public function handle(FriendCallback $event)
    {
        if ($event->operation == true) {
            $this->createFriend($event->accept_user, $event->request_user);
        }
    }

    public function createFriend($accept_user, $request_user)
    {
        DB::beginTransaction();
        try {
            Friend::firstOrCreate([
                'user_id' => $accept_user->id,
                'friend_id' => $request_user->id
            ], [
                'user_id' => $accept_user->id,
                'friend_id' => $request_user->id,
                'friend_name' => $request_user->name
            ]);

            Friend::firstOrCreate([
                'user_id' => $request_user->id,
                'friend_id' => $accept_user->id
            ], [
                'user_id' => $request_user->id,
                'friend_id' => $accept_user->id,
                'friend_name' => $accept_user->name
            ]);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new BaseException([
                'msg' => '创建好友失败，请稍后再试'
            ], 408);
        }
    }
}
