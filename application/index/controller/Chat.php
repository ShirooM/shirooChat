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
use think\Session;

class Chat extends Base
{
    public function index(){
        $username=Session::has('username')?Session::get('username'):$this->request->param('username');
        if(empty($username)){
            $this->error('您没有输入昵称。','index/index/index');
        }
        Session::set('username',$username);//把昵称存入session中。
        $uid=Session::has('uid')?Session::get("uid"):0;
        $this->assign('uid',$uid);
        if($uid>0){
            //$this->assign('userCount',Gateway::getAllClientCount());
            //$this->assign('userList',Gateway::getAllClientSessions());
            //这里判断一下是PC端打开，还是移动端打开
            if($this->request->isMobile()){
                return $this->fetch('index_mobile');
            }else{
                return $this->fetch();
            }
        }else{
            //取不到UID的话，表示没有登录，跳转到登录页面
            $this->redirect('index/chat/login');
        }
    }
    public function login(){
        if(!Session::has('uid')){
            $uid=rand(10000,99999);//用户的UID
            Session::set('uid',$uid);
            $this->success('登录成功，UID：'.$uid,'index/chat/index');
        }else{
            $uid=Session::get('uid');
            $this->success('已经登录，UID：'.$uid,'index/chat/index');
        }
    }
    public function bind(){
        //这里进行用户绑定
        //这里先判断客户是否处于登录状态。否则断开连接。
        $client_id=$this->request->param('client_id');
        $uid=Session::get('uid');
        $username=Session::get('username');

        Gateway::bindUid($client_id,$uid);//这里进行UID与Client_id的绑定

        // 假设用户已经登录，用户uid和群组id在session中
        Gateway::setSession($client_id,array(
            'uid'=>$uid,
            'username'=>$username
        ));

        try{
            //把该用户的UID发送给所有人
            Gateway::sendToAll(json_encode(array(
                'type'=>CHAT_TYPE_ENTER,
                //'client_id'=>$client_id,
                'uid'=>$uid,
                'user_count'=>Gateway::getAllClientCount(),
                'data'=>Gateway::getAllClientSessions()
            )));
        }catch (\Exception $e){
            Log::error($uid.'向所有人发送消息失败。[index/chat/bind]');
        }
    }
    public function onMessage(){
        //收到信息
        $post_message=$this->request->param('client_message');
        //如果发送得有下面的参数，则表示私聊该用户
        $post_uid=$this->request->param('post_uid')?:0;

        //取用户自己的UID
        $uid=Session::get('uid');
        $username=Session::get('username');

        //$client_id=Gateway::getClientIdByUid($uid);//这里会获到的是一个数组数据，比如一个浏览器打开两个页面就会出现两个client_id绑定在一个uid上。

        if($post_uid>0){
            //私聊
            try{
                Gateway::sendToUid($post_uid,$post_message);
            }catch (\Exception $e){

            }
        }else{
            //公聊
            try{
                Gateway::sendToAll(json_encode(array(
                    'type'=>CHAT_TYPE_MESSAGE,
                    //'client_id'=>$client_id,
                    'uid'=>$uid,
                    'username'=>$username,
                    'data'=>$post_message
                )));
            }catch (\Exception $e){

            }
        }
    }
}