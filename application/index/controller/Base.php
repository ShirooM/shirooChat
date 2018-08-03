<?php
/**
 * Created by PhpStorm.
 * User: xkq72
 * Date: 2018/3/20
 * Time: 14:32
 */

namespace app\index\controller;


use think\Controller;
use think\Request;
use think\Session;

class Base extends Controller
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        //定义各种常量
        define('CHAT_TYPE_MESSAGE','message');//消息类型
        define('CHAT_TYPE_ENTER','enter');//客户进入类型
        define('GATEWAY_SERVER_ADDRESS','127.0.0.1:1238');
        $this->request->isAjax()?define('IS_AJAX',true):define('IS_AJAX',false);
        $this->request->isPost()?define('IS_POST',true):define('IS_POST',false);
        $this->request->isGet()?define('IS_GET',true):define('IS_GET',false);
        $this->request->isMobile()?define('IS_MOBILE',true):define('IS_MOBILE',false);

        //Gateway::$registerAddress=GATEWAY_SERVER_ADDRESS;

    }
    public function getLoginData(){
        return Session::get('login');
    }
    public function setLoginPage($actions=array()){
        $action=$this->request->action();
        if(in_array($action,$actions)){
            //如果当前访问的action在数组中，则表示页面不需要登录验证即可访问

        }else{
            //必须登录验证通过才能访问
            $loginData=$this->getLoginData();//先获取用户登录信息
            $uid=$loginData['uid'];//获取用户的UID
            if(empty($uid)){
                //取不到UID的话，表示没有登录，跳转到登录页面
                $this->redirect('index/user/login');
            }
        }
    }
}