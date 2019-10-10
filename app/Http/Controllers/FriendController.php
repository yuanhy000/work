<?php

namespace App\Http\Controllers;

use App\Events\AddFriend;
use App\Events\FriendCallback;
use App\Exceptions\BaseException;
use App\Friend;
use App\FriendGroup;
use App\Http\Resources\FriendGroupCollection;
use App\Notification;
use App\User;
use Exception;
use Illuminate\Http\Request;

class FriendController extends Controller
{

    public function addFriend(Request $request)
    {
        $request_user = auth()->guard('api')->user();
        $accept_id = (int)$request->getContent('user_id');

        $isExist = Notification::NotificationIsExist($request_user->id, $accept_id, 'add-friend');
        if (!$isExist) {
            event(new AddFriend($request_user->id, $accept_id));
        }
        return response()->json([
            'msg' => '好友申请已发送，正在等待回复'
        ], 200);
    }


    public function addFriendCallback(Request $request)
    {
        $accept_user = auth()->guard('api')->user();

        $requestInfo = json_decode($request->getContent(), true);
        $request_user = $requestInfo['request_user'];
        $notification_id = (int)$requestInfo['notification_id'];
        $operation = (boolean)$requestInfo['operation'];

        event(new FriendCallback($request_user, $accept_user, $notification_id, $operation));

        return response()->json([
            'msg' => '通知操作成功'
        ], 200);
    }

    public function addFriendGroup(Request $request)
    {
        try {
            $user_id = auth()->guard('api')->user()->id;
            $name = $request->getContent('input');
            FriendGroup::create([
                'user_id' => $user_id,
                'name' => $name
            ]);
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '创建好友分组失败，请稍后再试'
            ], 408);
        }
        $friendGroup = FriendGroup::where('user_id', '=', $user_id)->with('friends')->get();
        return new FriendGroupCollection($friendGroup);
    }

    public function renameFriendGroup(Request $request)
    {
        try {
            auth()->guard('api')->user();
            $requestInfo = json_decode($request->getContent('input'), true);
            $name = $requestInfo['input'];
            $group_id = $requestInfo['group_id'];
            $friend_group = FriendGroup::where('id', '=', $group_id)->first();
            $friend_group->name = $name;
            $friend_group->save();
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '重命名好友分组失败，请稍后再试'
            ], 408);
        }
        return response()->json([
            'msg' => '操作成功'
        ], 205);
    }

    public function deleteFriendGroup(Request $request)
    {
        try {
            auth()->guard('api')->user();
            $group_id = $request->getContent('group_id');
            $friend_group = FriendGroup::where('id', '=', $group_id)->first();
            $friend_group->delete();
        } catch (Exception $exception) {
            throw new BaseException([
                'msg' => '删除好友分组失败，请稍后再试'
            ], 408);
        }
        return response()->json([
            'msg' => '操作成功'
        ], 205);
    }
}
