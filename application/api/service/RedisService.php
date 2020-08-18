<?php
namespace app\api\service;

use think\Cache;

class RedisService
{
    //获取缓存
    public function getRedisParam($param){
        return Cache::store('redis')->get($param);
    }
    //删除缓存
    public function rmRedisParam($param){
        return Cache::store('redis')->rm($param);
    }
    //设置缓存
    public function setRedisParam($param,$value){
        return Cache::store('redis')->set($param, $value);
    }
    //获取服务器
    public function getServer($table){
        //判断桌子的服务器
        $table_data = $this->getRedisParam('table_'.$table);
        if(!empty($table_data)){
            //桌子已分配服务器,判断服务器是否可用
            $server_use = $this->getRedisParam('server_use');
            if(in_array($table_data['server'], $server_use)){
                return $table_data['server'];
            }else{
                //使用中的服务器，去掉将要维护的服务器
                $server_maintain = $this->getRedisParam('server_maintain');
                $result=array_diff_assoc($server_use,$server_maintain);
                shuffle($result);
                $table_data['server'] = $result[0];
                $this->setRedisParam('table_'.$table, $table_data);
                return $result[0];
            }
        }else{
            //桌子未分配服务器
            $server_use = $this->getRedisParam('server_use');
            $server_maintain = $this->getRedisParam('server_maintain');
            $result=array_diff_assoc($server_use,$server_maintain);
            shuffle($result);
            $table_data['server'] = $result[0];
            $this->setRedisParam('table_'.$table, $table_data);
            return $result[0];
        }
    }
}