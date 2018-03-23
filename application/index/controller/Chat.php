<?php
/**
 * Created by PhpStorm.
 * User: xkq72
 * Date: 2018/3/23
 * Time: 19:59
 */

namespace app\index\controller;


class Chat extends Base
{
    public function index(){
        return $this->fetch();
    }

}