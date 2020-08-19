<?php
namespace app\api\controller;

use app\api\service\RedisService;

class Index extends Common
{
    //获取桌子服务器
    public function tableServer()
    {
        $table = $this->request('table');
        $redis = new RedisService();
        $server = $redis->getTableServer($table);
        if(!empty($server)){
            $this->success('',$server);
        }else{
            $this->error('没有可用的服务器');
        }
    }
    
    //获取用户连接的服务器
    public function userServer(){
        $user_id = $this->request('user_id');
        $redis = new RedisService();
        $server = $redis->getUserServer($user_id);
        if(!empty($server)){
            $this->success('',$server);
        }else{
            $this->error('没有可用的服务器');
        }
    }
}
