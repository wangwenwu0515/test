<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Controller
{
    //空操作
    public function _empty()
    {
        $this->redirect('Error/empty');
    }
    //首页
    public function index()
    {
        $data = array(
            'msg'=>"你好，这是后台首页"
        );
        $this->assign("data", $data);
        return $this->fetch('index');
    }
}
