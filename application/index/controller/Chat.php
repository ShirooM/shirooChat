<?php
/**
 * Created by PhpStorm.
 * User: xkq72
 * Date: 2018/3/23
 * Time: 19:59
 */

namespace app\index\controller;
use GatewayClient\Gateway;
use think\Log;
use think\Request;

class Chat extends Base
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->setLoginPage(array());
    }

    public function index(){
        $loginData=$this->getLoginData();

        /*if(empty($loginData['uid'])){
            //取不到UID的话，表示没有登录，跳转到登录页面
            $this->redirect('index/user/login');
        }*/
        $this->assign('uid',$loginData['uid']);//这里是赋值给页面的用于做页面的一些判断使用。
        //$this->assign('userCount',Gateway::getAllClientCount());
        //$this->assign('userList',Gateway::getAllClientSessions());
        //这里判断一下是PC端打开，还是移动端打开
        if($this->request->isMobile()){
            return $this->fetch('index_mobile');
        }else{
            return $this->fetch();
        }

    }
    public function bind(){
        //这里进行用户绑定
        //从客户端获取用户传递过来的参数
        $client_id=$this->request->param('client_id')?:'';
        if(empty($client_id)){
            return shiroo_json(-10001,'参数有误，中止执行。');
        }
        //设置GatewayWorker服务的Register服务ip和端口
        Gateway::$registerAddress=GATEWAY_SERVER_ADDRESS;
        //获取当前登录的账号信息
        $loginData=$this->getLoginData();
        $uid=$loginData['uid'];
        $username=$loginData['username'];
        Gateway::bindUid($client_id,$uid);//这里进行UID与Client_id的绑定

        //把一些相关数据设置到Gateway的Session中。
        Gateway::setSession($client_id,array(
            'uid'=>$uid,
            'username'=>$username
        ));

        try{
            //把该用户的UID，用户总数，等信息推送给所有人
            Gateway::sendToAll(json_encode(array(
                'type'=>CHAT_TYPE_ENTER,
                //'client_id'=>$client_id,
                'uid'=>$uid,
                'user_count'=>Gateway::getAllClientCount(),
                'data'=>Gateway::getAllClientSessions()
            )));
        }catch (\Exception $e){
            Log::error($uid.'向所有人发送消息失败。[index/chat/bind]');
            return shiroo_json(-10002,'向所有人推送登录信息失败。');
        }
    }
    public function onMessage(){
        //收到信息
        //获取客户端发送过来的消息。
        $post_message=$this->request->param('client_message')?:null;
        if(empty($post_message)){
            //如果为空的话。
            return shiroo_json(-10001,'发送的消息为空。');
        }

        //对发送过来的数据进行过滤处理
        $message=$post_message;
        $message=str_ireplace('<img','[img]',$message);
        $message=strip_tags($message);
        $message=str_ireplace('[img]','<img',$message);

        //如果发送得有下面的参数，则表示私聊该用户
        $post_uid=$this->request->param('post_uid')?:0;
        //获取登录的账号信息
        $loginData=$this->getLoginData();
        $uid=$loginData['uid'];
        $username=$loginData['username'];

        //设置GatewayWorker服务的Register服务ip和端口
        Gateway::$registerAddress=GATEWAY_SERVER_ADDRESS;

        //$client_id=Gateway::getClientIdByUid($uid);//这里会获到的是一个数组数据，比如一个浏览器打开两个页面就会出现两个client_id绑定在一个uid上。
        if($post_uid>0){
            //私聊
            try{
                Gateway::sendToUid($post_uid,json_encode(array(
                    'type'=>CHAT_TYPE_MESSAGE,
                    //'client_id'=>$client_id,
                    'uid'=>$uid,
                    'username'=>$username,
                    'data'=>$message
                )));
            }catch (\Exception $e){
                return shiroo_json(-10002,'发送消息失败。');
            }
        }else{
            //公聊
            try{
                Gateway::sendToAll(json_encode(array(
                    'type'=>CHAT_TYPE_MESSAGE,
                    //'client_id'=>$client_id,
                    'uid'=>$uid,
                    'username'=>$username,
                    'data'=>$message
                )));
            }catch (\Exception $e){
                return shiroo_json(-10002,'发送消息失败。');
            }
        }
    }
}