<?php
namespace app\index\controller;


class Index extends Base
{
    public function index()
    {
        $this->redirect('index/chat/index');
        //return $this->fetch();
    }
}
