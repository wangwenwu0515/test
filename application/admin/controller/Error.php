<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;

//空控制器
class Error extends Controller
{
    public function index(Request $request)
    {
        return '操作错误';
    }
    
    public function empty(){
        return $this->fetch('empty');
    }
}
