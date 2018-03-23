<?php
/**
 * Created by PhpStorm.
 * User: xkq72
 * Date: 2018/3/21
 * Time: 14:53
 */
namespace app\push\controller;

use think\worker\Server;

class Worker extends Server
{
    protected $socket='websocket://127.0.0.1:2346';

    public function onConnect($connection){
        echo 'shiroo.have p';
    }
    public function onMessage($connection,$data){
        $connection->send($data);
    }
    public function onWorkerStart($worker){

    }
}