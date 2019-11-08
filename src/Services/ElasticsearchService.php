<?php

namespace App\Services;

use Elasticsearch\ClientBuilder;
/**
 * elasticsearch 官方库单例类
 * @package App\Services
 */
class ElasticsearchService
{

    public static $_instance = null;

    public function __construct($elastic)
    {
        /*if (empty(self::$_instance) && $GLOBALS['app']->has('elastic_hosts')) {
            self::$_instance = ClientBuilder::create()
                ->setHosts($GLOBALS['app']->get('elastic_hosts')) //设置集群列表
                ->setRetries(0) //设置重试次数
                ->build();
        }*/
        return self::$_instance = $elastic;
    }


    public static function __callStatic($method, $arguments)
    {
        if(!self::$_instance){
            new static();
        }

        if(method_exists(self::$_instance,$method)){
            return call_user_func_array(array(self::$_instance, $method), $arguments);
        }else{
            throw new \Exception('Unexpected method call');
        }
    }
}