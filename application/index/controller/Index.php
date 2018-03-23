<?php
namespace app\index\controller;
use GatewayClient\Gateway;

class Index extends Base
{
    public function index()
    {
        return $this->fetch();
    }
    public function abc(){
        $message=$this->request->param('message');
        try{
            Gateway::sendToAll(json_encode(array(
                'type'=>'message',
                'client_id'=>'',
                'message'=>$message
            )));
        }catch (\Exception $e){

        }
    }
    public function bind(){
        $client_id=$this->request->param('client_id');

        // 假设用户已经登录，用户uid和群组id在session中
        try{
            Gateway::sendToAll(json_encode(array(
                'type'=>'message',
                'client_id'=>$client_id,
                'message'=>'login:'.$client_id
            )));
        }catch (\Exception $e){

        }
    }
}
