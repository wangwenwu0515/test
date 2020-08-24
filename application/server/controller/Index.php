<?php

namespace app\server\controller;

use Workerman\Worker;
use Workerman\Lib\Timer;

// 心跳间隔55秒
define('HEARTBEAT_TIME', 55);

class Index
{
    protected $socket = 'websocket://0.0.0.0:2346';
    
    public function index(){
        // 创建一个Worker监听2346端口，使用websocket协议通讯
        $ws_worker = new Worker("websocket://0.0.0.0:2346");
            
        // 启动4个进程对外提供服务
        $ws_worker->count = 4;
        
        // 当收到客户端发来的数据后返回hello $data给客户端
        $ws_worker->onMessage = function($connection, $data)
        {
            // 给connection临时设置一个lastMessageTime属性，用来记录上次收到消息的时间
            $connection->lastMessageTime = time();
            $controller = $data['controller'];
            $action = $data['action'];
            $da = $data['data'];
            $return = action($controller."/".$action,$da);
            // 向客户端发送hello $data
            $connection->send($return);
        };
        
        // 当客户端与Workerman建立连接时(TCP三次握手完成后)触发的回调函数
        $ws_worker->onConnect = function($connection)
        {
            echo "new connection from ip " . $connection->getRemoteIp() . "\n";
        };
        
        // 当客户端连接与Workerman断开时触发的回调函数
        $ws_worker->onClose = function($connection)
        {
            echo "connection closed\n";
        };
        
        // 当客户端的连接上发生错误时触发。
        $ws_worker->onError = function($connection, $code, $msg)
        {
            echo "error $code $msg\n";
        };
        
        
        // 进程启动后设置一个每10秒运行一次的定时器
        $ws_worker->onWorkerStart = function($worker) {
            Timer::add(10, function()use($worker){
                $time_now = time();
                foreach($worker->connections as $connection) {
                    // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
                    if (empty($connection->lastMessageTime)) {
                        $connection->lastMessageTime = $time_now;
                        continue;
                    }
                    // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
                    if ($time_now - $connection->lastMessageTime > HEARTBEAT_TIME) {
                        $connection->close();
                    }
                }
            });
        };
        
        
        // 运行worker
        Worker::runAll();
    }
}