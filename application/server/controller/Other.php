<?php
namespace app\server\controller;

class Other extends Common
{
    public function getUserInfo()
    {
        $id = $this->request('id');
        //处理
        $data = array(
            'code'=>0,
            'msg'=>"",
            'data'=>array(
                'name'=>'1111111'
            )
        );
        return json_encode($data);
    }
}
