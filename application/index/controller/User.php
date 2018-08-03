<?php
/**
 * Created by PhpStorm.
 * User: xkq72
 * Date: 2018/4/3
 * Time: 17:25
 */

namespace app\index\controller;


use think\Request;
use think\Session;

class User extends Base
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->setLoginPage(array('login','loginout'));
    }

    public function login(){
        //判断是否已经登录。
        $uid=Session::has('login.uid')?Session::get('login.uid'):0;
        if($uid>0){
            //已登录
            $this->success('已经登录，UID：'.$uid,'index/chat/index');
        }else{
            //没有登录
            if(IS_POST){
                //从客户端获取用户输入的昵称
                $username=$this->request->param('username')?:null;
                if(empty($username)){
                    $this->error('您没有输入昵称。','index/user/login');
                }
                Session::set('login.username',$username);//把昵称存入session中。
                //暂时随机分配UID，表示已登录。
                $uid=time().rand(100,999);//用户的UID
                Session::set('login.uid',$uid);
                $this->success('登录成功，UID：'.$uid,'index/chat/index');
            }else{
                //如果不是POST提交，则直接显示登录页面
                return $this->fetch();
            }
        }
    }
    public function loginOut(){
        $jumpURL=$this->request->param('jumpurl')?:'index/chat/index';
        Session::delete('login');
        $this->success('退出成功.',$jumpURL);
    }
}