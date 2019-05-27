<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\User\UserCommonController;
use App\Http\Models\ChatLog;
use App\Http\Models\GeChatGroup;
use App\Http\Models\GeFriend;
use App\Http\Models\GeFriendGroup;
use App\Http\Models\GeGroup;
use App\Http\Models\GeMessage;
use App\Http\Models\GeUser;
use Illuminate\Http\Request;
use GatewayClient\Gateway;
use Illuminate\Support\Facades\Input;

class GatewayEventsController extends UserCommonController
{
    //

    public function __construct()
    {
        Gateway::$registerAddress = '127.0.0.1:1314';

    }
    public function init(Request $request)
    {
        $message = $request->all();
        $message_type = $message['type'];

        switch ($message_type) {
            case 'init' :
                $uid = $request->session()->get('GEEK');
                $client_id = $message['client_id'];
                Gateway::sendToUid($uid, $message);
                Gateway::bindUid($client_id, $uid);  // uid 与 room_id 已经从 Laravel session里获取
                Gateway::sendToUid($uid, json_encode(array(
                    'type'      => 'notice',
                    'content' => 'init success ！',
                )));
                $request->session()->put([
                    'client_id' => $client_id,
                    'username' => $message['username'],
                    'avatar' => $message['avatar'],
                    'sign' => $message['sign']
                ]); // Laravel 负责
                Gateway::setSession($client_id, [                      // GatewayWorker 负责
                    'id' => $uid,
                    'username' => $message['username'],
                    'avatar' => $message['avatar'],
                    'sign' => $message['sign']
                ]);
                /*$_SESSION['uid'] = $uid;
                $_SESSION['username'] = $message['username'];
                $_SESSION['avatar'] = $message['avatar'];
                $_SESSION['sign'] = $message['sign'];*/
                //查询最近1周有无需要推送的离线信息
                $resMsg = ChatLog::where([
                    ['to_id','=',$uid],
                    ['need_send','=',1]
                ])->get();
                //var_export($resMsg);
                if (!empty($resMsg)) {
                    foreach ($resMsg as $vo) {
                        $log_message = [
                            'type' => 'logMessage',
                            'data' => [
                                'username' => $vo->from_name,
                                'avatar' => $vo->from_avatar,
                                'id' => $vo->from_id,
                                'type' => $vo->type,
                                'content' => htmlspecialchars($vo->content),
                                'timestamp' => strtotime($vo->created_at)*1000,
                            ]
                        ];
                        Gateway::sendToUid($uid, json_encode($log_message));
                        //设置推送状态为已经推送

                    }
                    ChatLog::where('to_id','=',$uid)->update(['need_send' => 0]);
                }
                //获取它的所有朋友的id
                $friendsAll = \App\Http\Models\GeFriend::where([
                    ['friend_id', '=', $uid],
                ])->pluck('user_id');
                if (!empty($friendsAll)) {
                    foreach ($friendsAll as $vo) {
                        $user_client_id = Gateway::getClientIdByUid($vo);
                        if (!empty($user_client_id)) {
                            $online_message = [
                                'type' => 'online',
                                'id' => $uid,
                            ];
                            Gateway::sendToUid($vo, json_encode($online_message));
                        }
                    }
                }
                //分组没看懂
                $ret = GeChatGroup::where('user_id','=',$uid)->get();
                if (!empty($ret)) {
                    foreach ($ret as $key => $vo) {
                        Gateway::joinGroup($client_id, $vo->group_id);  //将登录用户加入群组
                    }
                }
                unset($ret);
                //设置用户为登录状态
                $post = GeUser::where('id','=',$uid)->first();
                $post -> status = 1;
                $post -> save();
                return;
                break;
            case 'online':
                // 在线切换状态id
                $uid = $message['id'];
                //数据库状态保存
                if ($message['status'] == 'hide')
                    GeUser::where('id','=',$uid)->update(['status' => 2]);
                else{
                    GeUser::where('id','=',$uid)->update(['status' => 1]);
                }
                //获取它的所有朋友的id
                $friendsAll = \App\Http\Models\GeFriend::where([
                    ['friend_id', '=', $uid],
                ])->pluck('user_id');
                /*$online_message = [
                    'type' => ('online' == $message['status']) ? 'online' : 'hide',
                    'id' => $uid,
                ];
                Gateway::sendToUid($uid,json_encode($online_message));*/
                if (!empty($friendsAll)) {
                    foreach ($friendsAll as $vo) {
                        $user_client_id = Gateway::getClientIdByUid($vo);
                        if (!empty($user_client_id)){
                            $online_message = [
                                'type' => ('online' == $message['status']) ? 'online' : 'offline',
                                'id' => $uid,
                            ];
                            Gateway::sendToUid($vo, json_encode($online_message));
                        }
                    }
                }
                return;
                break;
            case 'addFriend':
                $uid = $message['id'];
                $client_id = Gateway::getClientIdByUid($message['toid']);
                $uinfo = GeUser::where('id','=',$uid)->first();
                if(!empty($client_id)){
                    $add_message = [
                        'type' => 'addFriend',
                        'data' => [
                            'username' => $uinfo->user_name,
                            'avatar' => $uinfo->avatar,
                            'id' => $uinfo->id,
                            'type' => 'friend',
                            'sign' => $uinfo->sign,
                            'groupid' => $message['GroupID'],
                        ]
                    ];
                    Gateway::sendToClient($client_id['0'], json_encode($add_message));
                }
                break;
            case 'addGroup':
                //判断群员是否已经在群里
                $hasUserInGroup = GeChatGroup::where([
                    ['user_id','=',$message['join_id']],
                    ['group_id','=',$message['id']]
                ])->exists();
                if (!$hasUserInGroup){
                    $groupInfoCtr = GeGroup::where('id','=',$message['id'])->first();
                    $join_user_info = GeUser::where('id','=',$message['join_id'])->first();
                    $input = [
                        'user_id' => $join_user_info->id,
                        'user_name' => $join_user_info->user_name,
                        'user_avatar' => $join_user_info->avatar,
                        'user_number' => $join_user_info->number,
                        'user_sign' => $join_user_info->sign,
                        'group_name' => $groupInfoCtr->group_name,
                        'group_id' => $groupInfoCtr->id,
                        'group_avatar' => $groupInfoCtr->group_avatar,
                    ];
                    $post = GeChatGroup::create($input);
                    if($post == false){
                        return abort(500);
                        break;
                    }
                }
                //入库后追加到主面板
                $client_id = Gateway::getClientIdByUid($message['join_id']);
                Gateway::joinGroup($client_id['0'], $message['id']); // 将该用户加入群组
                $add_message = [
                    'type' => 'addGroup',
                    'data' => [
                        'type'      => 'group',
                        'avatar'    => $message['avatar'],
                        'id'        => $message['id'],
                        'groupname' => $message['groupname']
                    ]
                ];
                Gateway::sendToUid($message['join_id'], json_encode($add_message));
                return json_encode(['code' => 0, 'msg' => 'success']);
                break;
            case 'chatMessage':
                // 聊天消息
                $toObj = json_decode($message['to']);
                $mineObj = json_decode($message['mine']);
                $type = $toObj->type;
                $to_id = $toObj->id;
                $uid = $request->session()->get('GEEK');
                //$sessions = Gateway::getSession($uid);
                $chat_message = [
                    'type' => 'chatMessage',
                    'data' => [
                        'username' => $mineObj->username,
                        'avatar' => $mineObj->avatar,
                        'id' => $type === 'friend' ? $uid : $to_id,
                        'from_id' => $uid,
                        'type' => $type,
                        'content' => htmlspecialchars($mineObj->content),
                        'timestamp' => time() * 1000,
                    ]
                ];
                // 加入聊天log表

                $param = [
                    'from_id' => $uid,
                    'to_id' => $to_id,
                    'from_name' => $mineObj->username,
                    'from_avatar' => $mineObj->avatar,
                    'content' => htmlspecialchars($mineObj->content),
                    'need_send' => 0
                ];
                switch ($type) {
                    // 私聊
                    case 'friend':
                        // 插入
                        $param['type'] = 'friend';
                        if (empty(Gateway::getClientIdByUid($to_id))) {
                            $param['need_send'] = 1;  //用户不在线,标记此消息推送
                            if (ChatLog::where([
                                ['from_id','=',$uid],
                                ['to_id','=',$to_id],
                                ['need_send','=',1]
                            ])->count() == 0){
                                $system =[
                                    'type' => 'chatMessage',
                                    'data' => [
                                        'system' => true//系统消息
                                        ,'id' => $to_id//聊天窗口ID
                                        ,'type' => "friend"//聊天窗口类型
                                        ,'content' => '对方已掉线'
                                    ]
                                ];
                                Gateway::sendToUid($uid, json_encode($system));
                            }
                        }
                        $post = ChatLog::create($param);
                        if ($post){
                            Gateway::sendToUid($to_id, json_encode($chat_message));
                        }
                        break;
                    // 群聊
                    case 'group':
                        $param['type'] = 'group';
                        $post = ChatLog::create($param);
                        if ($post){
                            Gateway::sendToGroup($to_id, json_encode($chat_message));
                        }
                        break;
                }
                break;
        }
    }
}
