<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\User\UserCommonController;
use App\Http\Models\ChatLog;
use App\Http\Models\GeBlackTab;
use App\Http\Models\GeChatGroup;
use App\Http\Models\GeDistrict;
use App\Http\Models\GeFriend;
use App\Http\Models\GeFriendGroup;
use App\Http\Models\GeGroup;
use App\Http\Models\GeMessage;
use App\Http\Models\GeReport;
use App\Http\Models\GeUser;
use App\Http\Models\GeZoneComment;
use App\Http\Models\GeZoneBlog;
use GatewayClient\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\ElasticSearchHandler;
use PHPUnit\Framework\Constraint\IsNan;

class UserIndexController extends UserCommonController
{
    //
    public function __construct()
    {
        Gateway::$registerAddress = '127.0.0.1:1314';

    }
    public function index(Request $request)
    {
        if ($request->isMethod('GET')) {

            $uid = $request->session()->get('GEEK');
            $uinfo = GeUser::where('id', '=', $uid)->first();
            if($uinfo == null){
                return redirect('gechat/login');
            }
            if (GeFriendGroup::where([
                    ['fid', '=', $uid],
                    ['group_id', '=', 0]
                ])->first() == null) {
                $newGroup = array(
                    'fid' => $uid,
                    'group_id' => 0,
                    'group_name' => '我的好友',
                );
                $groupPost = GeFriendGroup::create($newGroup);
                if ($groupPost == false) {
                    return abort(500);
                }
                $newFriend = array(
                    'user_id' => $uid,
                    'friend_id' => $uid,
                    'group_id' => 0,
                    'group_name' => '我的好友',
                );
                $friendPost = GeFriend::create($newFriend);
                if ($friendPost == false) {
                    return abort(500);
                }
                GeUser::where('id', '=', $uid)->update(['avatar' => '/images/avatar.png']);
            }
            $group = GeFriendGroup::where('fid', '=', $uid)->get();
            $shield = GeBlackTab::where('owner_id', '=', $uid)->get();
            return view('user.index', compact('uinfo', 'group', 'shield'));


        } elseif ($request->isMethod('POST')) {
            return (abort('405', '非法请求！'));
        } else {

            return (abort('405', '非法请求！'));

        }

    }

    public function user_login(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('user.login');
        } elseif ($request->isMethod('POST')) {

            $request->session()->forget('GeChatNumber');
            $input = $request->all();
            //定义规则
            $rules = array(
                'form-user' => 'bail|required|alpha_dash|between:6,20|exists:users,number',
                'form-pwd' => 'bail|required|alpha_dash|between:6,20',
                //'form-repwd' => 'bail|required|alpha_dash|between:6,20',
                //'captcha' => 'required|captcha',
            );
            //自定义错误信息
            $messages = array(
                'required' => ':attribute 不能为空.',
                'alpha_dash' => ':attribute只能包含字母和数字，以及破折号和下划线.',
                'between' => ':attribute 长度必须在 :min 和 :max 之间.',
                //'captcha' => ':attribute 不正确',
                'exists' => ':attribute 不存在'
            );
            //解释字段名
            $attributes = array(
                "form-user" => '用户名',
                'form-pwd' => '密码',
                //'form-repwd' => '密码',
                //'captcha' => '验证码',
            );
            $validator = Validator::make($input, $rules, $messages, $attributes);
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $userNumCtr = $request->input('form-user');
            $userPwdCtr = $request->input('form-pwd');
            $user = GeUser::where('number', '=', $userNumCtr)->first();
            if (decrypt($user->pwd) != $userPwdCtr) {
                return back()->with('error', '密码不匹配');
            }
            if(!empty(Gateway::getClientIdByUid($user->id))){
                $notice_message = [
                    'type' => 'account_conflict',
                    'msg' => '您的账号在其他地方成功登录，您被迫下线'
                ];
                Gateway::sendToUid($user->id,json_encode($notice_message));
            }
            $request->session()->forget('GEEK');
            $request->session()->regenerate();
            $request->session()->put('GEEK', $user->id);
            GeUser::where('id', '=', $user->id)->update(['status' => 1]);
            return redirect('gechat/index');

        } else {

            return (abort('405', '非法请求！'));

        }

    }

    public function user_register(Request $request)
    {
        if ($request->isMethod('GET')) {
            $ip = $request->ip();
            $myCity = $this->position($ip);
            $cityID = null;
            $local = [];
            if ('中国' != $myCity['data']['country']) {
                $local = ['北京市', '北京市', '东城区'];  //默认定位北京东城
            } else {
                $area_info = GeDistrict::where('parent_code', '=', $myCity['data']['city_id'])->first();
                if ($area_info != null) {
                    $cityID = $area_info->code;
                    $province = $area_info->province;
                    $city = $area_info->city;
                    $district = $area_info->district;
                    $local = [$province, $city, $district];
                } else {
                    $local = ['北京市', '北京市', '东城区'];
                }
            }
            unset($myCity);
            return view('user.register', compact('local'));
        } elseif ($request->isMethod('POST')) {

            $input = $request->all();
            //定义规则
            $rules = array(
                'form-username' => 'bail|required|between:1,10',
                'form-pwd' => 'bail|required|alpha_dash|between:6,20',
                'form-repwd' => 'bail|required|alpha_dash|between:6,20',
                'form-sex' => 'bail|required|numeric',
                'form-district' => 'bail|required',);
            //自定义错误信息
            $messages = array(
                'required' => ':attribute 不能为空.',
                'alpha_dash' => ':attribute只能包含字母和数字，以及破折号和下划线.',
                'between' => ':attribute 长度必须在 :min 和 :max 之间.',
                'numeric' => ':attribute只能是数字.',
                'integer' => ':attribute只能是整数.',
            );
            //解释字段名
            $attributes = array(
                'form-username' => '昵称',
                'form-pwd' => '密码',
                'form-repwd' => '密码',
                'form-sex' => '性别',
                'form-district' => '地区',
            );
            $validator = Validator::make($input, $rules, $messages, $attributes);
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
            $geChatNumber = '';
            for (; ;) {
                $geChatNumber = substr_replace($this->makeNumber(8), 1, 0, 1);
                if (GeUser::where('number', '=', $geChatNumber)->count() == 0) {
                    break;
                }
            }
            //方便入库，我定义了一些变量看起来更直观
            $user_name = $input['form-username'];
            $Pwd = $input['form-pwd'];
            $rePwd = $input['form-repwd'];
            $sex = $input['form-sex'];
            $district = $input['form-district'];
            //逻辑判断
            if ($Pwd != $rePwd){
                return back()->with('error', '两次输入的密码不相同');
            }
            $newUser = array(
                'number' => $geChatNumber,
                'user_name' => $user_name,
                'avatar' => url('images/avatar.png'),
                'pwd' => encrypt($rePwd),
                'sex' => $sex,
                'city' => $this->getDistrict($district)[1],
                'area' => $district,
            );
            $userPost = GeUser::create($newUser);
            if ($userPost == false){
                return back()->with('error', '系统错误');
            }
            $request->session()->put('GeChatNumber', $geChatNumber);
            return back()->with('success', '注册成功');

        } else {

            return (abort('405', '非法请求！'));

        }

    }

    public function getList(Request $request)
    {
        //$uid = session('f_user_id');
        $uid = $request->session()->get('GEEK');
        $mine = GeUser::where('id', '=', $uid)->first();
        $friends = [];
        $friendList = [];
        $group = [];  //记录群组信息
        $groupCtr_u = GeChatGroup::where('user_id', '=', $uid)->get();
        if(!empty($groupCtr_u)){
            foreach ($groupCtr_u as $key => $v){
                $groupInfo = GeGroup::where('id','=',$v->group_id)->first();
                $group[$key]['groupname'] = $groupInfo->group_name;
                $group[$key]['id'] = $v->group_id;
                $group[$key]['avatar'] = $groupInfo->group_avatar;
            }
        }
        //$userGroup = config('user_group');
        $list = [];  //群组成员信息
        $group_List = GeFriendGroup::where('fid', '=', $uid)->get();//获取该用户的所有分组
        foreach ($group_List as $key => $v) {
            //当前分组的所有好友
            $friendsAll = GeFriend::where([
                ['user_id', '=', $uid],
                ['group_id', '=', $v->group_id],
            ])->get();
            //依次便利一个个好友的信息
            $friendList = null;
            foreach ($friendsAll as $key2 => $f) {
                $friendCtr = GeUser::where('id', '=', $f->friend_id)->first();
                $statusCtr = '';
                switch ($friendCtr->status) {
                    case '0' :
                        $statusCtr = 'offline';
                        break;
                    case '1' :
                        $statusCtr = 'online';
                        break;
                    case '2' :
                        $statusCtr = 'offline';
                        break;
                    default :
                        $statusCtr = 'offline';
                        break;
                }
                $friendList[$key2] = [ //分组下的好友列表
                    'username' => $friendCtr->user_name //好友昵称
                    , 'id' => $friendCtr->id//好友ID
                    , 'avatar' => $friendCtr->avatar//好友头像
                    , 'sign' => $friendCtr->sign//好友签名
                    , 'status' => $statusCtr//若值为offline代表离线，online或者不填为在线
                    ,];
            }
            $friends[$key] = [
                'groupname' => $v->group_name,
                'id' => $v->group_id,
                'list' => $friendList
            ];
        }
        unset($friends_id, $group_name);
        $statusCtr = 'online';
        if ($mine['status'] == 2) {
            $statusCtr = 'hide';
        }
        $return = [
            'code' => 0,
            'msg' => '',
            'data' => [
                'mine' => [
                    'username' => $mine['user_name'],
                    'id' => $mine['id'],
                    'status' => $statusCtr,
                    'sign' => $mine['sign'],
                    'avatar' => $mine['avatar']
                ],
                'friend' => $friends,
                'group' => $group,
            ],
        ];
        return json_encode($return);
    }

    public function chatUser(Request $request)
    {
        if ($request->isMethod('GET')) {
            $uid = $request->session()->get('GEEK');
            $districtCtr = GeUser::where('id', '=', $uid)->first()->area;
            $local = '';
            if (!empty($districtCtr)) {
                $local = $this->getDistrict($districtCtr);
            } else {
                $local = ['北京市', '北京市', '东城区'];
            }
            $uinfo = GeUser::where('id', '=', $request->session()->get('GEEK'))->first();
            return view('user.profile', compact('uinfo', 'local'));

        } elseif ($request->isMethod('POST')) {
            /*$input = Input::all();
            echo (123456);
            return back()->with('success','修改成功！');*/
            return (abort(405));
        } else {
            return (abort(405));
        }
    }

    public function doChange(Request $request)
    {
        $uid = $request->session()->get('GEEK');
        $input = $request->all();
        //定义规则
        $rules = array(
            'captcha' => 'required|captcha',
            'user_name' => 'required|alpha_dash',
            'user_avatar' => 'nullable',
            'file' => 'nullable',
            'sex' => 'bail|required|numeric',
            'district' => 'bail|required',
            'form-birth' => 'nullable|date' ,
            'form-tel' => 'nullable|size:11',
            'form-email' => 'nullable|email|unique:users,email',
            'form-QQ' => 'nullable|between:6,12' ,
            );
        //自定义错误信息
        $messages = array(
            'captcha' => '验证码不正确.',
            'required' => ':attribute 不能为空.',
            'numeric' => ':attribute只能是数字.',
            'integer' => ':attribute只能是整数.',
            'date'    => ':attribute只能是日期格式.',
            'size'    => ':attribute长度必须满足 :size 位.',
            'unique' => ':attribute已存在.',
            'between' => ':attribute长度必须在 :min 到 :max 之间.'
        );
        //解释字段名
        $attributes = array(
            'sex' => '性别',
            'age' => '年龄',
            'district' => '地区',
            'form-birth' => '生日' ,
            'form-tel' => '电话号' ,
            'form-email' => '邮箱',
            'form-constellation' => '星座' ,
            'form-blood-type' => '血型' ,
            'form-QQ' => 'QQ号码' ,
            'captcha' => '验证码',
        );
        $validator = Validator::make($input, $rules, $messages, $attributes);
        if ($validator->fails()) {
            return redirect('gechat/chatuser/index')
                ->withErrors($validator)
                ->withInput();
        }
        //更新入库
        $post = GeUser::where('id', '=', $uid)->first();
        $age = $this->getAge($input['form-birth']);
        $poem = array(
            'user_name' => $input['user_name'],
            'avatar' => $input['user_avatar'],
            'sex' => $input['sex'],
            'age' =>$age !=false ? $age : -1,
            'city' => $this->getDistrict($input['district'])[1] ? $this->getDistrict($input['district'])[1] : '',
            'area' => $input['district'],

            'birth' => $input['form-birth'],
            'tel' => $input['form-tel'],
            'email' => '',
            'constellation' => $input['form-constellation'],
            'QQ' => $input['form-QQ'],
            'blood_type' => $input['form-blood-type'],
        );
        if ($post->update($poem)) {
            return redirect('gechat/chatuser/index')->with('success', '修改成功');
        } else {
            return redirect('gechat/chatuser/index')->with('error', '系统错误');
        }
    }

    public function upAvatar()
    {
        $file = Input::file('file');
        //设置要上传到服务器的位置
        $severPath = base_path() . '/public/uploads/UserAvatars';
        //判断文件是否有效
        if ($file->isValid()) {
            //获取临时文件的绝对路径
            $realPath = $file->getRealPath();
            //获取上传文件的后缀
            $entension = $file->getClientOriginalExtension();
            //获取文件类型
            $mimeTye = $file->getMimeType();
            $fileTypes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
            if (!in_array($mimeTye, $fileTypes)) {
                $response = [
                    'code' => '1',
                    'msg' => '上传的文件不合法.',
                    'data' => [
                        'src' => null,
                    ],
                ];
                return json_encode($response);
            }
            //设置允许的最大值
            $maxSize = 533504;
            //获取文件大小
            $size = $file->getClientSize();
            if ($size > $maxSize) {
                $response = [
                    'code' => '1',
                    'msg' => '图片超过了设定的最大值' . $size . '字节.',
                    'data' => [
                        'src' => null,
                    ],
                ];
                return json_encode($response);
            }

            //上传文件到目录并重命名，时分秒+3位数随机数
            $newName = date('YmdHis') . mt_rand(100, 999) . '.' . $entension;
            $path = $file->move($severPath, $newName);
            //图片的绝对位置
            $filepath = '/uploads/UserAvatars/' . $newName;
            $src = url($filepath);
            $response = [
                'code' => '0',
                'msg' => '上传成功.',
                'data' => [
                    'src' => $src,
                ],
            ];
            return json_encode($response);
        } else {

            //[ 'status'=>'100','errorMsg'=>'文件类型不匹配已设置的文件类型的限制','errorString'=>'INVALID_FILETYPE']
            //[ 'status'=>'200','errorMsg'=>'文件的大小超过所设定的上限','errString'=>'FILE_EXCEEDS_SIZE_LIMIT']
            $response = [
                'code' => '1',
                'msg' => '上传的文件不完整.',
                'data' => [
                    'src' => null,
                ],
            ];
            return json_encode($response);
        }
    }

    public function changeSign(Request $request)
    {
        $uid = $request->session()->get('GEEK');
        $signCtr = $request->input('sign');
        //从数据库取用户信息
        $post = GeUser::where('id', '=', $uid)->first();
        $post->sign = $signCtr;
        if ($post->save()) {
            $response = [
                'code' => '0',
                'msg' => '修改成功.',
            ];
            return json_encode($response);

        } else {
            $response = [
                'code' => '1',
                'msg' => '修改失败.',
            ];
            return json_encode($response);

        }

    }

    public function findGroup(Request $request)
    {
        if ($request->isMethod('GET')) {
            $uid = $request->session()->get('GEEK');
            $ip = $request->ip();
            $myCity = $this->position($ip);
            $local = [];
            $city = '';
            if ('中国' != $myCity['data']['country']) {
                $city = '北京市';
                $local = ['北京市', '北京市', '东城区'];  //默认定位北京东城
            } else {
                $area_info = GeDistrict::where('parent_code', '=', $myCity['data']['city_id'])->first();
                if ($area_info != null) {
                    $province = $area_info->province;
                    $city = $area_info->city;
                    $district = $area_info->district;
                    $local = [$province, $city, $district];
                } else {
                    $city = '北京市';
                    $local = ['北京市', '北京市', '东城区'];
                }
            }
            $unknownFriends = GeUser::where([
                ['city', '=', $city]
                , ['id', '<>', $uid]
            ])->inRandomOrder()->take(4)->get();
            $unknownsGroups = GeGroup::where('status', '=', '1')->inRandomOrder()->take(4)->get();
            $assign = ([
                'friends' => $unknownFriends,
                'groups' => $unknownsGroups,
                'person_status' => 'active',
                'group_status' => '',
                'target' => ''
            ]);
            return view('user.findgroup', compact('assign'));
        } elseif ($request->isMethod('POST')) {

            $input = Input::all();
            $uid = $request->session()->get('GEEK');
            $ip = $request->ip();
            //信息数组
            $local = [];
            //城市ID
            $city = '';
            $myCity = $this->position($ip);
            if ('中国' != $myCity['data']['country']) {
                $city = '北京市';
                $local = ['北京市', '北京市', '东城区'];  //默认定位北京东城
            } else {
                $area_info = GeDistrict::where('parent_code', '=', $myCity['data']['city_id'])->first();
                if ($area_info != null) {
                    //省
                    $province = $area_info->province;
                    //市
                    $city = $area_info->city;
                    //区
                    $district = $area_info->district;
                    //存入数组
                    $local = [$province, $city, $district];
                } else {
                    $city = '北京市';
                    $local = ['北京市', '北京市', '东城区'];
                }
            }
            $unknowns = GeUser::where([
                ['city', '=', $city]
                , ['id', '<>', $uid]
            ])->inRandomOrder()->take(4)->get();
            $unknownsGroups = GeGroup::where('status', '=', '1')->inRandomOrder()->take(4)->get();
            if ($request->input('type') == 'friend') {
                $resultsFriend = $input['form-target-friend'];
                $resultsFriendCtr = GeUser::where('number', '=', $resultsFriend)
                    ->orWhere('user_name', '=', $resultsFriend)
                    ->get();
                $assign = ([
                    'friends' => $unknowns,
                    'person_status' => 'active',
                    'group_status' => '',
                    'resultsF' => $resultsFriendCtr,
                    'resultsG' => '',
                    'groups' => $unknownsGroups,
                ]);
                return view('user.findgroup', compact('assign'));
            } elseif ($request->input('type') == 'group') {
                $resultsGroup = $input['form-target-group'];
                $resultsGroupCtr = GeGroup::where('group_num', '=', $resultsGroup)
                    ->orWhere('group_name', '=', $resultsGroup)
                    ->get();
                $assign = ([
                    'friends' => $unknowns,
                    'person_status' => '',
                    'group_status' => 'active',
                    'resultsF' => '',
                    'resultsG' => $resultsGroupCtr,
                    'groups' => $unknownsGroups,
                ]);
                return view('user.findgroup', compact('assign'));

            } else {
                return abort(405);
            }

        } else {
            return (abort(405));
        }

    }

    public function addFriend($tid, Request $request)
    {
        if ($request->isMethod('GET')) {

            $uid = $request->session()->get('GEEK');
            $friend = GeUser::where('id', '=', $tid)->first();
            $group = GeFriendGroup::where('fid', '=', $uid)->get();
            return view('user.addfriend', compact('friend', 'group'));
        } else {
            return (abort(405));
        }
    }

    public function applyFriend(Request $request)
    {
        if ($request->isMethod('POST')) {
            $uid = $request->session()->get('GEEK');
            $input = Input::all();
            $black_confirm = GeBlackTab::where([
                ['owner_id', '=', $input['form-target']],
                ['put_id', '<>', $uid],
            ])->first();
            if (!empty($black_confirm)) {
                return back()->with('status', '您已被对方加入黑名单');
            }
            $isFriend = GeFriend::where([
                ['user_id', '=', $uid],
                ['friend_id', '=', $input['form-target']]
            ])->first();
            if (!empty($isFriend)) {
                return back()->with('status', '你们已经是好友啦！');
            }
            $repeateDetection = GeMessage::where([
                ['from', '=', $uid],
                ['uid', '=', $input['form-target']],
                ['agree', '=', 0]
            ])->exists();
            if ($repeateDetection) {
                return back()->with('status', '请不要重复发送好友请求');
            }
            $inputSql = [
                'content' => '请求添加你为好友',
                'from' => $uid,
                'uid' => $input['form-target'],
                'from_group' => $input['form-group'],
                'type' => 1,
                'read' => 1,
                'agree' => 0,
                'remark' => $input['form-remark'],
            ];
            $post = GeMessage::create($inputSql);
            if ($post) {
                return back()->with('status', '成功发送添加好友请求');
            } else {
                return back()->with('status', '发送失败,请稍后重试');
            }
        } else {
            return (abort(405));
        }
    }

    public function msgIndex(Request $request)
    {

        $msg = GeMessage::where('uid', '=', $request->session()->get('GEEK'))->latest()->paginate(5);
        if (empty($msg)) {
            return view('user.msgbox', compact('msg'));
        }
        foreach ($msg as $key => $vo) {
            $msg[$key]['time'] = $vo->created_at;
            if (1 == $vo['type']) {
                $user = GeUser::where('id', '=', $vo['from'])->first();
                $msg[$key]['user'] = [
                    'id' => $vo['from'],
                    'avatar' => $user->avatar,
                    'username' => $user->user_name,
//                    'sign' => $user->sign
                ];
            } elseif (2 == $vo['type']) {
                $user = GeUser::where('id', '=', $vo['from'])->first();
                $msg[$key]['user'] = [
                    'id' => $vo['from'],
                    'avatar' => $user->avatar,
                    'username' => $user->user_name,
//                    'sign' => $user->sign
                ];
            } elseif (3 == $vo['type']) {
                $user = GeUser::where('id', '=', $vo['from'])->first();
                $msg[$key]['user'] = [
                    'id' => $vo['from'],
                    'avatar' => $user->avatar,
                    'username' => $user->user_name,
//                    'sign' => $user->sign
                ];
            }
        }
        return view('user.msgbox', compact('msg'));

    }

    public function msgGetNoRead(Request $request)
    {
        if ($request->isMethod('post')) {

            $tips = GeMessage::where([
                ['uid', '=', $request->session()->get('GEEK')],
                ['read', '=', 1],
            ])->count();
            return json_encode(['code' => 0, 'data' => $tips, 'msg' => 'success']);
        } else {
            return (abort(405));
        }
    }

    public function msgGetMsg(Request $request)
    {

        $msg = GeMessage::where('uid', '=', $request->session()->get('GEEK'))->latest()->get();
        if (empty($msg)) {
            return json_encode(['code' => 0, 'pages' => 0, 'data' => '', 'msg' => '没有未读消息.']);
        }
        foreach ($msg as $key => $vo) {
            $msg[$key]['time'] = date('Y-m-d H:i');
            if (1 == $vo['type']) {
                $user = GeUser::where('id', '=', $vo['from'])->first();
                $msg[$key]['user'] = [
                    'id' => $vo['from'],
                    'avatar' => $user->avatar,
                    'username' => $user->user_name,
                    'sign' => $user->sign
                ];
            } elseif (2 == $vo['type']) {
                $user = GeUser::where('id', '=', $vo['from'])->first();
                $msg[$key]['user'] = [
                    'id' => $vo['from'],
                    'avatar' => $user->avatar,
                    'username' => $user->user_name,
                    'sign' => $user->sign
                ];
            } elseif (3 == $vo['type']) {
                $user = GeUser::where('id', '=', $vo['from'])->first();
                $msg[$key]['user'] = [
                    'id' => $vo['from'],
                    'avatar' => $user->avatar,
                    'username' => $user->user_name,
                    'sign' => $user->sign
                ];
            }
        }
        return json_encode(['code' => 0, 'pages' => 1, 'data' => $msg]);
    }

    public function msgRead(Request $request)
    {
        if ($request->isMethod('POST')) {
            $read = Input::get('read');
            GeMessage::where('uid', $request->session()->get('GEEK'))
                ->where('read', 1)
                ->update(['read' => $read]);
        } else {
            return (abort(405));
        }
    }

    public function agreeFriend($tid, Request $request)
    {
        if ($request->isMethod('GET')) {

            $uid = $request->session()->get('GEEK');
            $friend = GeUser::where('id', '=', $tid)->first();
            $group = GeFriendGroup::where('fid', '=', $uid)->get();
            $friendGroupID = GeMessage::where([
                ['uid', '=', $uid]
                , ['from', '=', $tid]
                , ['type', '=', 1]
                , ['agree', '=', 0]
            ])->first()->from_group;
            return view('user.agreeFriend', compact('friend', 'group', 'friendGroupID'));
        } else {
            return (abort(405));
        }
    }

    public function agreeFriendEvents(Request $request)
    {
        if ($request->isMethod('POST')) {
            $uid = $request->session()->get('GEEK');
            $input = Input::all();
            $yid = $input['friendID'];//朋友的ID
            $myGroupID = $input['myGroupID'];
            $yourGroupID = $input['friendGroupID'];
            $black_confirm = GeBlackTab::where([
                ['owner_id', '=', $yid],
                ['put_id', '=', $uid],
            ])->first();
            if (!empty($black_confirm)) {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '您已被对方加入黑名单']);
            }
            $isFriend = GeFriend::where([
                ['user_id', '=', $uid],
                ['friend_id', '=', $yid]
            ])->first();
            if (!empty($isFriend)) {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '你们已经是好友啦！']);
            }
            $myGroupNameCtr = GeFriendGroup::where('group_id', '=', $myGroupID)->first()->group_name;
            $myFriend = [
                'user_id' => $uid,
                'friend_id' => $yid,
                'group_id' => $myGroupID,
                'group_name' => $myGroupNameCtr,
            ];
            $post = GeFriend::create($myFriend);
            if ($post == false) {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '系统错误']);
            }
            $yourGroupIdCtr = $yourGroupID;
            $yourGroupNameCtr = GeFriendGroup::where([
                ['fid', '=', $yid],
                ['group_id', '=', $yourGroupIdCtr],
            ])->first()->group_name;
            $yourFriend = [
                'user_id' => $yid,
                'friend_id' => $uid,
                'group_id' => $yourGroupIdCtr,
                'group_name' => $yourGroupNameCtr,
            ];
            $post = GeFriend::create($yourFriend);
            if ($post == false) {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '系统错误']);
            }
            $yourRemarkCtr = GeMessage::where([
                ['uid', '=', $uid]
                , ['type', '=', 1]
                , ['from', '=', $yid],
            ])->first()->remark;
            $inputSql = [
                'content' => '你的好友请求.',
                'from' => $uid,
                'uid' => $yid,
                'from_group' => $yourGroupIdCtr,
                'type' => 2,
                'read' => 1,
                'agree' => 1,
                'remark' => $yourRemarkCtr != null ? $yourRemarkCtr : '',
            ];
            $post = GeMessage::create($inputSql);
            if ($post) {
                GeMessage::where([
                    ['uid', $request->session()->get('GEEK')],
                    ['type', '=', 1],
                    ['from', $yid,
                    ]])->update(['agree' => 1]);
                $friend = GeUser::where('id', '=', $yid)->first();
                $friend->groupID = $myGroupID;
                $friend->mineID = $uid;
                $friend->code = 1;
                return json_encode(['code' => 0, 'data' => '', 'msg' => '已同意']);
            } else {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '系统错误']);
            }

        } else {

            return (abort(405));
        }
    }

    public function refuseFriend(Request $request)
    {
        if ($request->isMethod('POST')) {
            $uid = $request->session()->get('GEEK');
            $fromIdCtr = $request->input('from_id');
            $inputSqlYour = [
                'content' => '好友请求',
                'from' => $uid,
                'uid' => $fromIdCtr,
                'from_group' => 0,
                'type' => 2,
                'read' => 1,
                'agree' => 2,
                'remark' => '已拒绝',
            ];
            $post = GeMessage::create($inputSqlYour);
            if ($post == false) {
                return json_encode(['code' => 1, 'data' => '系统错误', 'msg' => 'success']);
            } else {
                GeMessage::where([
                    ['uid', $request->session()->get('GEEK')],
                    ['from', $fromIdCtr],
                    ['type', '=', 1]
                ])->update(['agree' => 2]);
                return json_encode(['code' => 0, 'data' => '', 'msg' => 'success']);
            }
        } else {
            return abort(403);
        }

    }

    public function addGroup(Request $request, $gid)
    {
        if ($request->isMethod('GET')) {
            $group = GeGroup::where('id', '=', $gid)->first();
            return view('user.addGroup', compact('group'));
        } elseif ($request->isMethod('POST')) {
            $input = $request->all();
            $uid = $request->session()->get('GEEK');
            $groupCtr = GeGroup::where('id', '=', $input['form-target-groupId'])->first();
            if ($groupCtr->setting == -1) {
                return back()->with('status', '群主设置了不允许加群');
            }
            $alreadInGroup = GeChatGroup::where([
                ['user_id', '=', $uid],
                ['group_id', '=', $input['form-target-groupId']],
            ])->exists();
            if ($alreadInGroup) {
                return back()->with('status', '你傻吖！你在群里了呀!');
            }
            $repeateDetection = GeMessage::where([
                ['from', '=', $uid],
                ['from_group', '=', $input['form-target-groupId']],
                ['agree', '=', 0]
            ])->exists();
            if ($repeateDetection) {
                return back()->with('status', '请不要重复发送加群请求');
            }
            $inputSql = [
                'content' => '请求加群：' . $input['form-target-groupName'],
                'from' => $uid,
                'uid' => $groupCtr->owner_id,
                'from_group' => $input['form-target-groupId'],
                'type' => 3,
                'read' => 1,
                'agree' => 0,
                'remark' => $input['form-remark'],
            ];
            $post = GeMessage::create($inputSql);
            if ($post) {
                return back()->with('status', '成功发送加群请求');
            } else {
                return back()->with('status', '发送失败,请稍后重试');
            }

        } else {
            return (abort(405));
        }

    }

    public function createGroup(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('user.createGroup');
        } elseif ($request->isMethod('POST')) {

            $uid = $request->session()->get('GEEK');
            $userCtr = GeUser::where('id', '=', $uid)->first();
            $input = $request->all();
            //定义规则
            $rules = array(
                'form-group-name' => 'bail|required|between:1,10',
                'form-group-profile' => 'bail|required|between:1,100',
            );
            //自定义错误信息
            $messages = array(
                'required' => ':attribute 不能为空.',
                'between' => ':attribute 长度必须在 :min 和 :max 之间.',
            );
            //解释字段名
            $attributes = array(
                'form-group-name' => '群组名',
                'form-group-profile' => '群组简介',
            );
            $validator = Validator::make($input, $rules, $messages, $attributes);
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
            //生产随机群号
            $geGroupNumber = '';
            for (; ;) {
                $geGroupNumber = substr_replace($this->makeNumber(6), 1, 0, 1);
                if (GeGroup::where('group_num', '=', $geGroupNumber)->count() == 0) {
                    break;
                }
            }
            if ($input['form-group-avatar'] == null) {
                $input['form-group-avatar'] = url('images/avatar.png');
            }
            $inputSql = [
                'group_num' => $geGroupNumber,
                'group_name' => $input['form-group-name'],
                'group_avatar' => $input['form-group-avatar'],
                'group_profile' => $input['form-group-profile'],
                'owner_id' => $uid,
                'status' => 1,
                'setting' => 0,
            ];
            $post = GeGroup::create($inputSql);
            if ($post) {
                $groupIdCtr = GeGroup::where([
                    ['group_num', '=', $geGroupNumber],
                ])->first();
                $groupNumSql = [
                    'user_id' => $uid,
                    'user_number' => $userCtr->number,
                    'group_id' => $groupIdCtr->id,
                    'group_number' => $geGroupNumber,
                    'power' => 9
                ];
                $post = GeChatGroup::create($groupNumSql);
                if ($post) {
                    $request->session()->flash('group_num', $geGroupNumber);
                    $request->session()->flash('group_name', $input['form-group-name']);
                    $request->session()->flash('avatar', $input['form-group-avatar']);
                    $request->session()->flash('group_id', $groupIdCtr->id);
                    return back()->with('status', 'success');
                }

            } else {
                return back()->with('status', '发送失败,请稍后重试');
            }

        } else {
            return abort(403);
        }
    }

    public function agreeGroup(Request $request)
    {
        if ($request->isMethod('POST')) {

            $from_id = $request->input('from_id');
            $groupId = $request->input('group_id');
            $fromInfoCtr = GeUser::where('id', '=', $from_id)->first();
            $groupCtr = GeGroup::where('id', '=', $groupId)->first();
            $detectRepeat = GeChatGroup::where([
                ['group_id', '=', $groupId],
                ['user_id', '=', $from_id]
            ])->exists();
            if ($detectRepeat) {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '请不要重复发送请求！']);
            }
            $groupNumSql = [
                'user_id' => $from_id,
                'user_number' => $fromInfoCtr->number,
                'group_id' => $groupCtr->id,
                'group_number' => $groupCtr->group_num,
                'power' => 0
            ];
            $post = GeChatGroup::create($groupNumSql);
            if ($post) {
                $groupMessageSql = [
                    'content' => '您的加群请求',
                    'from' => $request->session()->get('GEEK'),
                    'uid' => $from_id,
                    'from_group' => $groupId,
                    'type' => 3,
                    'read' => 1,
                    'agree' => 1,
                    'remark' => '已同意',
                ];
                $post = GeMessage::create($groupMessageSql);
                if ($post) {
                    GeMessage::where([
                        ['uid', $request->session()->get('GEEK')],
                        ['from', $from_id],
                        ['type', '=', 3]
                    ])->update(['agree' => 1]);
                    $data = array(
                        'avatar' => $groupCtr->group_avatar,
                        'groupName' => $groupCtr->group_name
                    );
                    return json_encode(['code' => 0, 'data' => $data, 'msg' => 'success']);
                }
            } else {
                return abort(500);
            }
        } else {
            return abort(403);
        }
    }

    public function refuseGroup(Request $request)
    {
        if ($request->isMethod('POST')) {
            $uid = $request->session()->get('GEEK');
            $fromIdCtr = $request->input('from_id');
            $fromGroupCtr = $request->input('from_group');
            $inputSqlYour = [
                'content' => '您的加群请求',
                'from' => $uid,
                'uid' => $fromIdCtr,
                'from_group' => $fromGroupCtr,
                'type' => 3,
                'read' => 1,
                'agree' => 2,
                'remark' => '已拒绝',
            ];
            $post = GeMessage::create($inputSqlYour);
            if ($post == false) {
                return json_encode(['code' => 1, 'data' => '系统错误', 'msg' => 'success']);
            } else {
                GeMessage::where([
                    ['uid', $request->session()->get('GEEK')],
                    ['from', $fromIdCtr],
                    ['type', '=', 3]
                ])->update(['agree' => 2]);
                return json_encode(['code' => 0, 'data' => '', 'msg' => 'success']);
            }

        } else {
            return abort(403);
        }

    }

    public function getMembers(Request $request)
    {
        $id = $request->input('id');
        $numberCtr = GeChatGroup::where('group_id', '=', $id)->get();
        $list = [];
        foreach ($numberCtr as $key => $vo) {
            $userInfo = GeUser::where('id','=',$vo->user_id)->first();
            $list[$key]['username'] = $userInfo->user_name;
            $list[$key]['avatar'] = $userInfo->avatar;
            $list[$key]['id'] = $vo->user_id;
            $list[$key]['sign'] = $userInfo->sign;
        }
        $return = [
            'code' => 0,
            'msg' => '',
            'data' => [
                'list' => $list
            ]
        ];
        return json_encode($return);
    }

    public function uploadImg()
    {
        $file = Input::file('file');
        //设置要上传到服务器的位置
        $severPath = base_path() . '/public/uploads/ChatLogImages';
        //判断文件是否有效
        if ($file->isValid()) {
            //获取临时文件的绝对路径
            $realPath = $file->getRealPath();
            //获取上传文件的后缀
            $entension = $file->getClientOriginalExtension();
            //获取文件类型
            $mimeTye = $file->getMimeType();
            $fileTypes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif');
            if (!in_array($mimeTye, $fileTypes)) {
                $response = [
                    'code' => 1,
                    'msg' => '上传的文件不合法.',
                    'data' => [
                        'src' => null,
                    ],
                ];
                return json_encode($response);
            }
            //设置允许的最大值
            $maxSize = 533504;
            //获取文件大小
            $size = $file->getClientSize();
            if ($size > $maxSize) {
                $response = [
                    'code' => 1,
                    'msg' => '此图片' . $size . '字节.' . '超过了设定的最大值',
                    'data' => [
                        'src' => null,
                    ],
                ];
                return json_encode($response);
            }
            //上传文件到目录并重命名，时分秒+3位数随机数
            $newName = date('YmdHis') . mt_rand(100, 999) . '.' . $entension;
            $path = $file->move($severPath, $newName);
            //图片的绝对位置
            $filepath = '/uploads/ChatLogImages/' . $newName;
            $src = url($filepath);
            $response = [
                'code' => 0,
                'msg' => '上传成功.',
                'data' => [
                    'src' => $src
                ],
            ];
            return json_encode($response);
        } else {

            //[ 'status'=>'100','errorMsg'=>'文件类型不匹配已设置的文件类型的限制','errorString'=>'INVALID_FILETYPE']
            //[ 'status'=>'200','errorMsg'=>'文件的大小超过所设定的上限','errString'=>'FILE_EXCEEDS_SIZE_LIMIT']
            $response = [
                'code' => '1',
                'msg' => '上传的文件不完整.',
                'data' => [
                    'src' => null,
                ],
            ];
            return json_encode($response);
        }

    }

    public function uploadFile()
    {
        $file = Input::file('file');
        //设置要上传到服务器的位置
        $severPath = base_path() . '/public/uploads/ChatLogFiles';
        //判断文件是否有效
        if ($file->isValid()) {
            //获取临时文件的绝对路径
            $realPath = $file->getRealPath();
            //获取上传文件的后缀
            $entension = $file->getClientOriginalExtension();
            //获取文件类型
            $mimeTye = $file->getMimeType();
            /*
            $fileTypes = array('image/jpeg','image/jpg','image/png','image/gif');
            if(!in_array($mimeTye,$fileTypes)) {
                $response =[
                    'code' => 1,
                    'msg'  => '上传的文件不合法.',
                    'data' => [
                        'src' => null,
                    ],
                ];
                return json_encode($response);
            }*/
            //设置允许的最大值
            $maxSize = 20971520;
            //获取文件大小
            $size = $file->getClientSize();
            if ($size > $maxSize) {
                $response = [
                    'code' => 1,
                    'msg' => '此文件' . $size . '字节.' . '超过了设定的最大值',
                    'data' => [
                        'src' => null,
                    ],
                ];
                return json_encode($response);
            }
            //上传文件到目录并重命名，时分秒+3位数随机数
            $newName = date('YmdHis') . mt_rand(100, 999) . '.' . $entension;
            $path = $file->move($severPath, $newName);
            //图片的绝对位置
            $filepath = '/uploads/ChatLogFiles/' . $newName;
            $src = url($filepath);
            $response = [
                'code' => 0,
                'msg' => '上传成功.',
                'data' => [
                    'src' => $src,
                    'name' => $file->getClientOriginalName()
                ],
            ];
            return json_encode($response);
        } else {

            //[ 'status'=>'100','errorMsg'=>'文件类型不匹配已设置的文件类型的限制','errorString'=>'INVALID_FILETYPE']
            //[ 'status'=>'200','errorMsg'=>'文件的大小超过所设定的上限','errString'=>'FILE_EXCEEDS_SIZE_LIMIT']
            $response = [
                'code' => '1',
                'msg' => '上传的文件不完整.',
                'data' => [
                    'src' => null,
                ],
            ];
            return json_encode($response);
        }

    }

    public function saveAudio()
    {
        $file = request()->file('audio-blob');
        //设置要上传到服务器的位置
        $severPath = base_path() . '/public/uploads/ChatLogAudios';
        //判断文件是否有效
        if ($file->isValid()) {
            //获取临时文件的绝对路径
            $realPath = $file->getRealPath();
            //获取上传文件的后缀
            $entension = $file->getClientOriginalExtension();
            //获取文件类型
            $mimeTye = $file->getMimeType();
            /*
            $fileTypes = array('image/jpeg','image/jpg','image/png','image/gif');
            if(!in_array($mimeTye,$fileTypes)) {
                $response =[
                    'code' => 1,
                    'msg'  => '上传的文件不合法.',
                    'data' => [
                        'src' => null,
                    ],
                ];
                return json_encode($response);
            }*/
            //设置允许的最大值
            $maxSize = 1048576;
            //获取文件大小
            $size = $file->getClientSize();
            if ($size > $maxSize) {
                $response = [
                    'code' => 1,
                    'msg' => '此文件' . $size . '字节.' . '超过了设定的最大值',
                    'data' => [
                        'src' => null,
                    ],
                ];
                return json_encode($response);
            }
            //上传文件到目录并重命名，时分秒+3位数随机数
            $newName = date('YmdHis') . mt_rand(100, 999) . '.' . $entension;
            $path = $file->move($severPath, $newName);
            //图片的绝对位置
            $filepath = '/uploads/ChatLogAudios/' . $newName;
            $src = url($filepath);
            $response = [
                'code' => 0,
                'msg' => '上传成功.',
                'data' => [
                    'src' => $src,
                ],
            ];
            return json_encode($response);
        } else {

            //[ 'status'=>'100','errorMsg'=>'文件类型不匹配已设置的文件类型的限制','errorString'=>'INVALID_FILETYPE']
            //[ 'status'=>'200','errorMsg'=>'文件的大小超过所设定的上限','errString'=>'FILE_EXCEEDS_SIZE_LIMIT']
            $response = [
                'code' => '1',
                'msg' => '上传的文件不完整.',
                'data' => [
                    'src' => null,
                ],
            ];
            return json_encode($response);
        }

    }

    public function deleteFriend(Request $request)
    {

        if ($request->isMethod('POST')) {

            $uid = $request->session()->get('GEEK');
            $friendID = $request->input('friendID');
            $deletedUser = GeFriend::where([
                ['user_id', '=', $uid],
                ['friend_id', '=', $friendID]
            ])->delete();
            $deletedFriend = GeFriend::where([
                ['user_id', '=', $friendID],
                ['friend_id', '=', $uid]
            ])->delete();
            if ($deletedFriend && $deletedUser) {
                return json_encode(['code' => 0, 'data' => '', 'msg' => '已将此人从您的好友列表移除']);
            } else {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '删除失败']);
            }
        } else {
            return abort(403);
        }
    }

    public function moveGroup(Request $request)
    {
        if ($request->isMethod('POST')) {
            $uid = $request->session()->get('GEEK');
            $move_friend_id = $request->input('move_friend_id');
            $to_group_id = $request->input('to_group_id');
            if($uid ==  $move_friend_id){
                return json_encode(['code' => 1, 'data' => '', 'msg' => '你坏(=^ ^=)不可以移动自己！']);
            };
            if(GeFriend::where([
                ['user_id', '=', $uid]
                , ['friend_id', '=', $move_friend_id]
                ,['group_id','=', $to_group_id]
            ])->exists()){
                return json_encode(['code' => 1, 'data' => '', 'msg' => '不需要移动哦！']);
            };
            $updateRes = GeFriend::where([
                ['user_id', '=', $uid]
                , ['friend_id', '=', $move_friend_id]
            ])->update(['group_name'=> GeFriendGroup::where([['fid','=',$uid],['group_id','=',$to_group_id]])->first()->group_name]);
            $updateRes2 = GeFriend::where([
                ['user_id', '=', $uid]
                , ['friend_id', '=', $move_friend_id]
            ])->update(['group_id' => $to_group_id]);
            if ($updateRes && $updateRes2) {
                $friendInfo = GeUser::where('id', '=', $move_friend_id)->first();
                $friendInfoArr = [
                    'avatar' => $friendInfo->avatar//头像
                    , 'user_name' => $friendInfo->user_name//昵称
                    , 'id' => $friendInfo->id//ID
                    , 'sign' => $friendInfo->sign//签名
                ];
                return json_encode(['code' => 0, 'data' => $friendInfoArr, 'msg' => '移动分组成功']);
            } else {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '移动分组失败']);
            }
        } else {
            return abort(403);
        }

    }

    public function getChatlog(Request $request)
    {
        if ($request->isMethod('GET')) {
            $tarID = $request->input('id');
            switch ($request->input('type')) {
                case 'friend':
                    $count = ChatLog::where([
                        ['from_id', '=', session('GEEK')],
                        ['to_id', '=', $tarID]
                    ])->orwhere([['from_id', '=', $tarID],
                        ['to_id', '=', session('GEEK')]])->count();
                    //$url = url('gechat/chatuser/chatlogs'.'/'.$toID);
                    $ID = $tarID;
                    $limit = 8;
                    $type = 'friend';
                    return view('user.chatlog', compact('count', 'ID', 'limit', 'type'));
                case 'group':
                    $count = ChatLog::where([
                        ['to_id', '=', $tarID]
                    ])->count();
                    //$url = url('gechat/chatuser/chatlogs'.'/'.$toID);
                    $ID = $tarID;
                    $limit = 8;
                    $type = 'group';
                    return view('user.chatlog', compact('count', 'ID', 'limit', 'type'));
            }
        } else {
            return abort(403);
        }
    }

    public function groupChatlogs($toID, Request $request)
    {
        if ($request->isMethod('GET')) {
            $count = ChatLog::where([
                ['from_id', '=', session('GEEK')],
                ['to_id', '=', $toID]
            ])->orwhere([['from_id', '=', $toID],
                ['to_id', '=', session('GEEK')]])->count();
            //$url = url('gechat/chatuser/chatlogs'.'/'.$toID);
            $ID = $toID;
            $limit = 8;
            $type = 'group';
            return view('user.chatlog', compact('count', 'ID', 'limit', 'type'));

        } else {
            return abort(403);
        }
    }

    public function chatlogs($toID, Request $request)
    {
        if ($request->isMethod('GET')) {
            $count = ChatLog::where([
                ['from_id', '=', session('GEEK')],
                ['to_id', '=', $toID]
            ])->orwhere([['from_id', '=', $toID],
                ['to_id', '=', session('GEEK')]])->count();
            //$url = url('gechat/chatuser/chatlogs'.'/'.$toID);
            $ID = $toID;
            $limit = 8;
            $type = 'friend';
            return view('user.chatlog', compact('count', 'ID', 'limit', 'type'));
        } elseif ($request->isMethod('POST')) {
            $type = $request->input('type');
            $limit = $request->input('limit');
            switch ($request->input('type')) {
                case 'friend':
                    $chatlog2 = ChatLog::where([
                        ['from_id', '=', session('GEEK')],
                        ['to_id', '=', $toID]
                    ])->orwhere([['from_id', '=', $toID],
                        ['to_id', '=', session('GEEK')]])->latest()->paginate($limit);
                    $data = [];
                    foreach ($chatlog2 as $key => $v) {
                        $data[$key] = [ //分组下的好友列表
                            'username' => $v->from_name //好友昵称
                            , 'id' => $v->from_id//好友ID
                            , 'avatar' => $v->from_avatar//好友头像
                            , 'timestamp' => strtotime($v->created_at) * 1000//好友签名
                            , 'content' => $v->content//若值为offline代表离线，online或者不填为在线
                            ,];
                    }
                    return json_encode(['code' => 0, 'data' => $data, 'msg' => '已返回对应页数']);
                case 'group':
                    $chatlog2 = ChatLog::where([
                        ['to_id', '=', $toID]
                    ])->paginate($limit);
                    $data = [];
                    foreach ($chatlog2 as $key => $v) {
                        $data[$key] = [ //分组下的好友列表
                            'username' => $v->from_name //好友昵称
                            , 'id' => $v->from_id//好友ID
                            , 'avatar' => $v->from_avatar//好友头像
                            , 'timestamp' => strtotime($v->created_at) * 1000//好友签名
                            , 'content' => $v->content//若值为offline代表离线，online或者不填为在线
                            ,];
                    }
                    return json_encode(['code' => 0, 'data' => $data, 'msg' => '已返回对应页数']);
            }
        } else {
            return abort(403);
        }
    }

    public function shield(Request $request)
    {
        if ($request->isMethod('POST')) {
            if (GeBlackTab::where([
                ['owner_id', '=', $request->session()->get('GEEK')],
                ['put_id', '=', $request->input('put_id')]
            ])->exists()) {
                $delete = GeBlackTab::where([
                    ['owner_id', '=', $request->session()->get('GEEK')]
                    , ['put_id', '=', $request->input('put_id')]
                    , ['type', '=', $request->input('type')]
                ])->delete();
                if ($delete) {
                    return json_encode(['code' => 0, 'data' => '', 'msg' => '已取消屏蔽，刷新页面即可生效']);
                } else {
                    return json_encode(['code' => 1, 'data' => '', 'msg' => '取消屏蔽失败，请稍后重试']);
                }
            }
            $input = [
                'owner_id' => $request->session()->get('GEEK'),
                'put_id' => $request->input('put_id'),
                'type' => $request->input('type')
            ];
            $post = GeBlackTab::create($input);
            if ($post) {
                return json_encode(['code' => 0, 'data' => '', 'msg' => '已屏蔽，刷新页面即可生效']);
            } else {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '屏蔽失败，请稍后重试']);
            }
        } else {
            return abort(403);
        }
    }

    public function destroyChatGroup(Request $request)
    {
        if ($request->isMethod('POST')) {
            $uid = $request->session()->get('GEEK');
            $tarID = $request->input('group_id');
            $groupTarget = GeGroup::where([
                ['id', '=', $tarID]
            ])->first();
            if ($groupTarget->owner_id == $uid) {
                $delete = GeGroup::where([
                    ['id', '=', $tarID]
                ])->delete();
                //$clear = GeChatGroup::where('group_id','=',$tarID)->delete();
                if ($delete) {
                    return json_encode(['code' => 0, 'data' => '', 'msg' => '已解散此群']);
                } else {
                    return json_encode(['code' => 1, 'data' => '', 'msg' => '解散群失败，请稍后重试']);
                }

            } else {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '你傻吖！你又不是群猪~']);
            }
        } else {

            return abort(403);
        }
    }

    public function lookFriend(Request $request, $frID)
    {
        if ($request->isMethod('GET')) {

            $friendInfo = GeUser::where('id', '=', $frID)->first();
            return view('user.friendProfile', compact('friendInfo'));
        } else {
            return abort(403);
        }
    }
    public function lookGroup(Request $request, $grID)
    {
        if ($request->isMethod('GET')) {
            $groupInfo = GeGroup::where([['id', '=', $grID]])->first();
            if ($groupInfo == null) {
                return view('error.404');
            }
            $groupUsersCtr = GeChatGroup::where([
                ['group_id', '=', $grID]
                ,['power', '=', 0]
            ])->get();
            $groupUsers = [];
            if (!empty($groupUsersCtr))
            foreach($groupUsersCtr as $key => $v){
                $groupUsers[$key] = GeUser::where([['id', '=',  $v->user_id]])->first();
            };
            $groupOwner = GeUser::where([
                ['id', '=',  $groupInfo->owner_id]
            ])->first();
            $groupMembers =
                [
                    "owner" => $groupOwner,
                    "admins" => '',
                    "members" => $groupUsers
                ];
            //dd($groupMembers['owner']->user_name);
            return view('user.groupProfile', compact('groupInfo', 'groupMembers'));
        } else {
            return abort(403);
        }
    }

    public function lookGzone(Request $request, $frID)
    {
        if ($request->isMethod('GET')) {
            $uinfo = GeUser::where('id','=',$frID)->first();
            $blogs = GeZoneBlog::where('post_id', '=', $frID)->first();
            $comments = [];
            if (!empty($blogs)){
                foreach ($blogs as $key => $v){
                    $comments[$key]['comContent'] = GeZoneComment::where([
                        ['user_id', '=', $frID]
                        ,['blog_id','=',$v->id]
                    ])->get();
                    $comments[$key]['commentator'] = GeUser::where([
                        ['id', '=', $v->com_id]
                    ])->first();
                }
            }
            return view('user.gzoneBlogs', compact('uinfo','blogs','comments'));
        }
        else{
            return abort(403);
        }
    }

    public function leaveChatGroup(Request $request)
    {
        if ($request->isMethod('POST')) {

            $uid = $request->session()->get('GEEK');
            $tarID = $request->input('groupID');
            if($uid = GeGroup::where([
                ['id', '=', $tarID]
            ])->first()->owner_id){
                return json_encode(['code' => 1, 'data' => '', 'msg' => '群主不能退群，您可以解散群组']);
            }
            $clear = GeChatGroup::where([
                ['group_id', '=', $tarID]
                , ['user_id', '=', $uid]
            ])->delete();
            if ($clear) {
                return json_encode(['code' => 0, 'data' => '', 'msg' => '已退出此群']);
            } else {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '退出群组失败，请刷新重试']);
            }

        } else {
            return abort(403);
        }
    }

    public function reportFriend($frID, Request $request)
    {
        if ($request->isMethod('GET')) {
            $friendInfo = GeUser::where('id', '=', $frID)->first();
            $type = 'friend';
            return view('user.report', compact('friendInfo', 'type'));
        } elseif ($request->isMethod('POST')) {

            $uid = $request->session()->get('GEEK');
            $contentCtr = $request->input('content');
            $input = [
                'report_uid' => $uid,
                'reported_uid' => $frID
                , 'content' => $contentCtr
                , 'type' => $request->input('type')
            ];
            if (GeReport::where([
                    ['report_uid', '=', $uid,]
                    , ['reported_uid', '=', $frID]
                ])->count() >= 10) {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '举报次数到达上限']);
            }
            if (GeReport::create($input)) {
                return json_encode(['code' => 0, 'data' => '', 'msg' => '举报成功,核实后我们将严肃处理！']);
            } else {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '举报失败，请稍后重试']);
            }


        } else {
            return abort(403);
        }
    }

    public function reportGroup($grID, Request $request)
    {

        if ($request->isMethod('GET')) {
            $groupInfo = GeGroup::where('id', '=', $grID)->first();
            $type = 'group';
            return view('user.report', compact('groupInfo', 'type'));
        } elseif ($request->isMethod('POST')) {
            $uid = $request->session()->get('GEEK');
            $contentCtr = $request->input('content');
            $input = [
                'report_uid' => $uid,
                'reported_uid' => $grID
                , 'content' => $contentCtr
                , 'type' => $request->input('type')
            ];
            if (GeReport::where([
                    ['report_uid', '=', $uid,]
                    , ['reported_uid', '=', $grID]
                ])->count() >= 10) {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '举报次数到达上限']);
            }
            if (GeReport::create($input)) {
                return json_encode(['code' => 0, 'data' => '', 'msg' => '举报成功,核实后我们将严肃处理！']);
            } else {
                return json_encode(['code' => 1, 'data' => '', 'msg' => '举报失败，请稍后重试']);
            }
        } else {
            return abort(403);
        }

    }

    public function logout(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->session()->forget('GEEK');
            return json_encode(['code' => 0, 'data' => '', 'msg' => '已清除您的登陆信息']);
        } else {
            return abort(403);
        }
    }

    public function switch(Request $request)
    {
        if ($request->isMethod('POST')) {
            $request->session()->forget('GEEK');
            return json_encode(['code' => 0, 'data' => '', 'msg' => '已切换']);
        } else {
            return abort(403);
        }
    }

    public function myChatGroup(Request $request)
    {
        if ($request->isMethod('GET')) {
            $uid = $request->session()->get('GEEK');
            $groups = GeGroup::where('owner_id', '=', $uid)->get();
            $groupNums = [];
            foreach ($groups as $key => $v) {
                $groupNums[$key] = GeChatGroup::where('group_id', '=', $v->id)->get();
            }
            $uinfo = GeUser::where('id', '=', $uid)->first();
            return view('user.ucenter', compact('groups', 'groupNums', 'uinfo'));
        } else {
            return abort(403);
        }
    }

    public function ucenter(Request $request)
    {
        if ($request->isMethod('GET')) {
            $uid = $request->session()->get('GEEK');
            $uinfo = GeUser::where('id', '=', $uid)->first();
            return view('user.ucenter', compact('uinfo'));
        } else {
            return abort(403);
        }
    }

    public function getChatGroupJSON(Request $request)
    {
        if ($request->isMethod('GET')) {
            $uid = $request->session()->get('GEEK');
            $groups = GeGroup::where('owner_id', '=', $uid)->get();
            $groupInfo = [];
            $groupInfoChildren = [];
            foreach ($groups as $key => $v) {
                $groupInfoChildren[$key] = [
                    "title" => $v->group_name,
                    "icon" => "&#xe61c;",
                    "href" => url('gechat/chatuser/getChatGroupInfo/' . $v->id),
                    "spread" => false
                ];
            }
            $return =
                [
                    "contentManagement" =>
                        [
                            [
                                "title" => "我创建的的群组",
                                "icon" => "&#xe613;",
                                "href" => url('404'),
                                "spread" => false,
                                "children" => $groupInfoChildren
                            ]
                            ,[
                            "title" => "地址本",
                            "icon" => "&#xe63c;",
                            "href" => url('404'),
                            "spread" => false,
                            "children" =>[
                                    [
                                        "title" => "百度",
                                        "icon" => "&#xe615;",
                                        "href" => "https://www.baidu.com/",
                                        "spread" => false,
                                    ],
                                    [
                                        "title" => "腾讯QQ",
                                        "icon" => "&#xe606;",
                                        "href" => "http://www.qq.com/",
                                        "spread" => false,
                                    ],
                                    [
                                        "title" => "极客凌云",
                                        "icon" => "&#xe609;",
                                        "href" => "http://www.geekadpt.cn/",
                                        "spread" => false,
                                    ]
                                ]
                            ]
                        ],
                    "memberCenter" =>
                        [
                            [
                                "title" => "用户中心",
                                "icon" => "&#xe612;",
                                "href" => "/",
                                "spread" => false
                            ],
                        ],
                ];
            return json_encode($return);
        } else {
            return abort(403);
        }
    }

    public function getChatGroupInfo($grID, Request $request)
    {
        if ($request->isMethod('GET')) {
            $uid = $request->session()->get('GEEK');
            $groupInfo = GeGroup::where(
                [
                    ['owner_id', '=', $uid]
                    , ['id', '=', $grID]
                ]
            )->first();
            if ($groupInfo == null) {
                return view('error.404');
            }
            //普通用户
            $groupUsersCtr = GeChatGroup::where([
                ['group_id', '=', $grID]
                ,['power', '=', 0]
            ])->get();
            $groupUsers = [];
            //遍历获取群成员信息
                if (!empty($groupUsersCtr))
                foreach($groupUsersCtr as $key => $v){
                    $groupUsers[$key] = GeUser::where([['id', '=',  $v->user_id]])->first();
                };
            //群主信息
            $groupOwner = GeUser::where([
                ['id', '=',  $groupInfo->owner_id]
            ])->first();
            //群所有成员，包括群主、管理员
            $groupMembers =
                [
                    "owner" => $groupOwner,
                    "admins" => '',
                    "members" => $groupUsers,
                    "count" => $groupUsersCtr->count()+1
                ];
            $uinfo = GeUser::where('id', '=', $uid)->first();
            return view('user.groupInfo', compact('groupInfo', 'groupMembers', 'uinfo'));
        } else {
            return abort(403);
        }
    }

    public function changePwd(Request $request)
    {
        if ($request->isMethod('POST')) {
            $uid = $request->session()->get('GEEK');
            $input = $request->all();
            $rules = array(
                'oldpwd' => 'bail|required|alpha_dash|between:6,20',
                'pwd' => 'bail|required|alpha_dash|between:6,20',
                'repwd' => 'bail|required|alpha_dash|between:6,20',
            );
            //自定义错误信息
            $messages = array(
                'required' => ':attribute 不能为空.',
                'alpha_dash' => ':attribute只能包含字母和数字，以及破折号和下划线.',
                'between' => ':attribute 长度必须在 :min 和 :max 之间.',
            );
            //解释字段名
            $attributes = array(
                "oldpwd" => '旧密码',
                'pwd' => '密码',
                'repwd' => '密码',
            );
            $validator = Validator::make($input, $rules, $messages, $attributes);
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }
            //方便入库，我定义了一些变量看起来更直观
            $oldPwdCtr = $input['oldpwd'];
            $Pwd = $input['pwd'];
            $rePwd = $input['repwd'];
            //从数据库取数据库中的旧密码
            $oldPwdSql = GeUser::where('id', '=', $uid)->first()->value('pwd');
            //逻辑判断
            if ($Pwd != $rePwd) {
                return back()->with('error', '两次输入的密码不相同');
            }
            if ($oldPwdCtr != decrypt($oldPwdSql)) {
                return back()->with('error', '旧密码不匹配');
            }
            if (decrypt($oldPwdSql) == $rePwd) {
                return back()->with('error', '新旧密码不能相同');
            }
            //更新入库
            $post = GeUser::where('id', '=', $uid)->first();
            $poem = array(
                'pwd' => encrypt($rePwd),
            );
            if ($post->update($poem)) {
                return back()->with('success', '密码修改成功');
            } else {
                return back()->with('error', '系统错误');
            }
        } else {
            return view('user.changePwd');
        }
    }

    public function changeGroupInfo(Request $request)
    {
        if ($request->isMethod('POST')) {
            $uid = $request->session()->get('GEEK');
            $groupIdCtr = $request->input('group_id');
            $contentCtr = $request->input('content');
            switch ($request->input('type')) {
                case 'group-name':
                    if (GeGroup::where([['id', '=', $groupIdCtr], ['owner_id', '=', $uid]])->update(['group_name' => $contentCtr])) {
                        return json_encode(['code' => 0, 'data' => $contentCtr, 'msg' => '已更新']);
                    } else {
                        return json_encode(['code' => 1, 'data' => $contentCtr, 'msg' => '更新失败']);
                    }
                    break;
                case 'group-profile':
                    if (GeGroup::where([['id', '=', $groupIdCtr], ['owner_id', '=', $uid]])->update(['group_profile' => $contentCtr])) {
                        return json_encode(['code' => 0, 'data' => $contentCtr, 'msg' => '更新成功']);
                    } else {
                        return json_encode(['code' => 1, 'data' => $contentCtr, 'msg' => '更新失败']);
                    }
                    break;
                case 'group-avatar':
                    if (GeGroup::where([['id', '=', $groupIdCtr], ['owner_id', '=', $uid]])->update(['group_avatar' => $contentCtr])) {
                        return json_encode(['code' => 0, 'data' => $contentCtr, 'msg' => '更新成功']);
                    } else {
                        return json_encode(['code' => 1, 'data' => $contentCtr, 'msg' => '更新失败']);
                    }
                    break;
            }
        } else {
            return abort(403);
        }
    }

    public function createFriendGroup(Request $request)
    {
        if($request->isMethod('POST')){

            $uid = $request->session()->get('GEEK');
            $friendGroupName =  $request->get('new_group_name');
            if($friendGroupName == '我的好友'){
                return json_encode(['code' => 1, 'data' =>'', 'msg' => '群组名称以存在，换一个试试吧']);
            }
            if(mb_strlen($friendGroupName,'utf-8') >= 11){
                return json_encode(['code' => 1, 'data' =>'', 'msg' => '分组名称过长']);
            }
            if(GeFriendGroup::where([
                ['fid','=',$uid]
                ,['group_name','=',$friendGroupName]
            ])->exists()){
                return json_encode(['code' => 1, 'msg' => '群组名称以存在，换一个试试吧']);
            }
            $maxGroupId = GeFriendGroup::where('fid','=',$uid)->orderby('group_id','desc')->first();
            $input = [
                'fid'=>$uid ,
                'group_id'=>$maxGroupId->group_id+1,
                'group_name'=>$friendGroupName,
            ];
            if(GeFriendGroup::create($input)){
                return json_encode(['code' => 0, 'data' =>$maxGroupId->group_id+1, 'msg' => '创建成功']);
            }
            else{
                return json_encode(['code' => 1, 'data' =>$maxGroupId->group_id+1, 'msg' => '创建失败，请稍后重试']);
            }

        }
        else{
            return abort(403);
        }
    }
    public function deleteFriendGroup(Request $request)
    {
        if($request->isMethod('POST')){

            $uid = $request->session()->get('GEEK');
            $friendGroupName =  $request->get('group_name');
            if($friendGroupName == '我的好友'){
                return json_encode(['code' => 1, 'data' =>'', 'msg' => '0号分组不允许删除！']);
            }
            if(GeFriend::where([
                ['group_name','=',$friendGroupName]
                ,['user_id','=',$uid]
            ])->count() != 0 ){
                GeFriend::where([
                    ['group_name','=',$friendGroupName]
                    ,['user_id','=',$uid]
                ])->update(['group_name' => '我的好友']);
                GeFriend::where([
                    ['group_name','=','我的好友']
                    ,['user_id','=',$uid]
                    ,['group_id','<>',0]
                ])->update(['group_id' => 0]);
            }
            if(GeFriendGroup::where([
                ['group_name','=',$friendGroupName]
                ,['fid','=',$uid]
            ])->delete()){
                return json_encode(['code' => 0, 'data' =>'', 'msg' => '删除成功']);
            }
            else{
                return json_encode(['code' => 1, 'data' =>'', 'msg' => '删除失败，请稍后重试']);
            }
        }
        else{
            return abort(403);
        }
    }
    public function renameFriendGroup(Request $request)
    {
        if($request->isMethod('POST')){
            $uid = $request->session()->get('GEEK');
            $oldFriendGroupName =  $request->get('old_group_name');
            $newFriendGroupName =  $request->get('new_group_name');
            if(mb_strlen($newFriendGroupName,'utf-8')  >= 11){
                return json_encode(['code' => 1, 'data' =>'', 'msg' => '分组名称过长']);
            }
            if($oldFriendGroupName == '我的好友' || $newFriendGroupName == '我的好友'){
                return json_encode(['code' => 1, 'data' =>'', 'msg' => '不允许对0号分组进行操作']);
            }
            if(GeFriendGroup::where([
                ['fid','=',$uid]
                ,['group_name','=',$newFriendGroupName]
            ])->exists()){
                return json_encode(['code' => 1, 'msg' => '群组名称以存在，换一个试试吧']);
            }
            if(GeFriend::where([
                    ['group_name','=',$oldFriendGroupName]
                    ,['user_id','=',$uid]
                ])->count() != 0 ){
                GeFriend::where([
                    ['group_name','=',$oldFriendGroupName]
                    ,['user_id','=',$uid]
                ])->update([
                    ['group_name' => $newFriendGroupName]
                ]);
            }
            if(GeFriendGroup::where([
                ['group_name','=',$oldFriendGroupName]
                ,['fid','=',$uid]
            ])->update(['group_name' => $newFriendGroupName])){
                return json_encode(['code' => 0, 'data' =>'', 'msg' => '已重命名']);
            }
            else{
                return json_encode(['code' => 1, 'data' =>'', 'msg' => '重命名失败']);
            }
        }
        else{
            return abort(403);
        }
    }








    public function notFound(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('error.404');
        } else {
            return abort(403);
        }
    }

    public function phpInfo()
    {
        echo ini_get('post_max_size');
    }
}