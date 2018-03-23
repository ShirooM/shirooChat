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
        Gateway::$registerAddress='127.0.0.1:1238';

    }
}