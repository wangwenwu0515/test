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
        $server = $redis->getServer($table);
        if(!empty($server)){
            $this->success('',$server);
        }else{
            $this->error('没有可用的服务器');
        }
    }
}
