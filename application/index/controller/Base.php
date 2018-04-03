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
use GatewayClient\Gateway;

class Base extends Controller
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        if(is_null($request)){
            $request=Request::instance();
        }
        //定义各种常量
        define('CHAT_TYPE_MESSAGE','message');//消息类型
        define('CHAT_TYPE_ENTER','enter');//客户进入类型

        Gateway::$registerAddress='127.0.0.1:1238';

    }
    public function setLoginData(){
        //登录成功后记录相关数据

    }
    public function unLoginData(){
        //退出后删除数据

    }
}