<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('gechat/index');
});
//管理员登陆
Route::get('admin/login', 'Admin\AdminIndexController@admin_login');
//用户登陆及注册路由
Route::any('gechat/login', 'User\UserIndexController@user_login');
Route::any('gechat/register', 'User\UserIndexController@user_register');
Route::any('gechat/test', 'TestController@test');
Route::get('404', 'User\UserIndexController@notFound');
Route::get('phpInfo', 'User\UserIndexController@phpInfo');
//用户登陆路由组，前缀、命名空间以及中间件！
//用户登陆路由组，前缀、命名空间以及中间件！
Route::group(['prefix' => 'gechat','namespace' => 'User', 'middleware' => 'user_login'], function () {
    Route::any('index', 'UserIndexController@index');
    Route::any('chatuser/index', 'UserIndexController@chatUser');
    Route::post('index/getList', 'UserIndexController@getList');
    Route::post('chatuser/dochange', 'UserIndexController@doChange');
    Route::post('chatuser/upavatar', 'UserIndexController@upAvatar');
    Route::post('chatuser/changeSign', 'UserIndexController@changeSign');
    Route::post('chatuser/init', 'GatewayEventsController@init');
    Route::any('chatuser/findgroup', 'UserIndexController@findGroup');
    Route::get('chatuser/addfriend/{tid}','UserIndexController@addFriend');
    Route::post('chatuser/applyfriend', 'UserIndexController@applyFriend');
    Route::get('msgbox/index','UserIndexController@msgIndex');
    Route::post('msgbox/getNoRead', 'UserIndexController@msgGetNoRead');
    Route::any('msgbox/getMsg', 'UserIndexController@msgGetMsg');
    Route::post('msgbox/read', 'UserIndexController@msgRead');
    Route::get('chatuser/agreefriend/{tid}','UserIndexController@agreeFriend');
    Route::post('chatuser/agreefriend/events','UserIndexController@agreeFriendEvents');
    Route::post('msgbox/refuseFriend', 'UserIndexController@refuseFriend');

    Route::any('chatuser/addGroup/{gid}','UserIndexController@addGroup');
    Route::post('chatuser/agreeGroup','UserIndexController@agreeGroup');
    Route::post('chatuser/refuseGroup','UserIndexController@refuseGroup');
    Route::any('chatuser/createGroup','UserIndexController@createGroup');
    Route::any('index/getMembers', 'UserIndexController@getMembers');

    Route::post('upload/uploadImg', 'UserIndexController@uploadImg');
    Route::post('upload/uploadFile', 'UserIndexController@uploadFile');
    Route::get('chatuser/chatlog', 'UserIndexController@getChatlog');

    Route::post('tools/saveAudio', 'UserIndexController@saveAudio');
    Route::post('chatuser/deleteFriend', 'UserIndexController@deleteFriend');
    Route::post('chatuser/moveGroup', 'UserIndexController@moveGroup');
    Route::any('chatuser/chatlogs/{toID}', 'UserIndexController@chatlogs');
    Route::any('chatuser/groupChatlogs/{toID}', 'UserIndexController@groupChatlogs');
    Route::post('chatuser/shield', 'UserIndexController@shield');
    Route::post('chatuser/destroyChatGroup', 'UserIndexController@destroyChatGroup');
    Route::get('chatuser/lookFriend/{frID}', 'UserIndexController@lookFriend');
    Route::get('chatuser/lookGroup/{grID}', 'UserIndexController@lookGroup');
    Route::get('chatuser/lookGzone/{frID}', 'UserIndexController@lookGzone');
    Route::post('chatuser/leaveChatGroup', 'UserIndexController@leaveChatGroup');
    Route::any('chatuser/reportFriend/{frID}', 'UserIndexController@reportFriend');
    Route::any('chatuser/reportGroup/{grID}', 'UserIndexController@reportGroup');
    Route::post('chatuser/logout', 'UserIndexController@logout');
    Route::post('chatuser/switch', 'UserIndexController@switch');
    Route::get('chatuser/getChatGroupInfo/{grID}', 'UserIndexController@getChatGroupInfo');
    Route::get('ucenter', 'UserIndexController@ucenter');
    Route::any('chatuser/getChatGroupJSON', 'UserIndexController@getChatGroupJSON');
    Route::any('chatuser/changePwd', 'UserIndexController@changePwd');
    Route::post('chatuser/changeGroupInfo', 'UserIndexController@changeGroupInfo');
    //好友分组三个接口FriendGroup为好友分组标识 ChatGroup为群组标识
    Route::post('chatuser/createFriendGroup', 'UserIndexController@createFriendGroup');
    Route::post('chatuser/deleteFriendGroup', 'UserIndexController@deleteFriendGroup');
    Route::post('chatuser/renameFriendGroup', 'UserIndexController@renameFriendGroup');
});

//管理员登陆路由组，前缀、命名空间以及中间件！
Route::group(['prefix' => 'admin','namespace' => 'Admin', 'middleware' => 'admin_login'], function () {

    Route::any('index', 'AdminIndexController@index');

});
//数据库抛出异常