<?php
namespace app\push\controller;

use Workerman\Worker;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Register;
use GatewayWorker\Gateway;

class Run
{
    public function __construct()
    {

        //由于是手动添加，因此需要注册命名空间，方便自动加载，具体代码路径以实际情况为准
        \think\Loader::addNamespace([
            'Workerman' => VENDOR_PATH . 'Workerman/workerman-for-win',
            'GatewayWorker' =>VENDOR_PATH . 'Workerman/gateway-worker-for-win/src',
        ]);

        //初始化register
        new Register('text://0.0.0.0:1238');

        //初始化 bussinessWorker 进程
        $worker = new BusinessWorker();
        $worker->name = 'YourAppBusinessWorker';
        $worker->count = 4;
        $worker->registerAddress = '127.0.0.1:1238';
        //设置处理业务的类,此处制定Events的命名空间
        $worker->eventHandler = 'Events';

        // 初始化 gateway 进程
        $gateway = new Gateway("websocket://0.0.0.0:2346");
        $gateway->name = 'YourAppGateway';
        $gateway->count = 4;
        $gateway->lanIp = '127.0.0.1';
        $gateway->startPort = 2900;
        $gateway->registerAddress = '127.0.0.1:1238';

        Worker::runAll();
    }
}