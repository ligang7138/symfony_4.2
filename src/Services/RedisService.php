<?php
namespace App\Services;

class RedisService {

    protected $redis;

    public static $redisCache;

    function __construct($redis)
    {
	    //$redis->auth('ys123456');
//        $this->redis = $redis;
        return self::$redisCache = $redis;
    }


    /**
     * 设置缓存值
     * @param string $key
     * @param string $value
     * @param int $expire_time 过期时间(秒) ，为空则默认为永久有效
     * @return boolean or string
     */
    public function cache($key, $value = NULL, $expire_time = 0)
    {
        $ret = NULL;
        if (!empty($value)) {
            $ret = self::$redisCache->set($key, serialize($value));
            if ($expire_time) {
                self::$redisCache->expire($key, $expire_time);
            }
        } else {
            $ret = unserialize(self::$redisCache->get($key));
        }
        return $ret;
    }

    public function __call($method, $arguments)
    {
        if(method_exists(self::$redisCache,$method)){
            return call_user_func_array(array(self::$redisCache, $method), $arguments);
        }else{
            throw new \Exception('Unexpected method call');
        }
    }

    /**
     * 删除缓存
     * @param string $key
     * @return boolean
     */
    public function delCache($key)
    {
        $ret = self::$redisCache->del($key);
        return $ret;
    }

    /**
     * 批量set多个值(去重)
     * @param array $values
     */
    public function setAddArray(string $key, array $values)
    {
        $pipe = self::$redisCache->pipeline();
        foreach ($values as $value) {
            $pipe->sAdd($key, $value);
        }
        return self::$redisCache->exec();
    }



    /**
     * 批量添加多个键值(去重)
     * @param array $values
     */
    public function addArrayKV(string $key, array $values)
    {
        $pipe = self::$redisCache->pipeline();
        foreach ($values as $k => $value) {
            $pipe->hset($key, $k, $value);
        }
        return self::$redisCache->exec();
    }

    /**
     * 将数组数据增加到hash
     * @param $key_pre
     * @param $data
     * @param string $key
     * @param int $batch_count
     */
    public function dataToHash($key_pre,$data,$key_name='id',$batch_count=100,$has_key=true){
        $current_time = time();

        if(count($data) == count($data,COUNT_RECURSIVE)){ //判断是否为一维数组

            $key = $data[$key_name];
            if(!$has_key){
                unset($data[$key_name]);
            }
            $this->hMset("{$key_pre}{$key}", $data);
        }else{
            $for_count = 1;
            $pipe = self::$redisCache->multi(self::PIPELINE);
            foreach ($data as $item){
                $key = $item[$key_name];
                if(!$has_key){
                    unset($item[$key_name]);
                }

                $pipe->hMset("{$key_pre}{$key}", $item);
                $pipe->zAdd("{$key_pre}KEYS", $current_time, $key);
                if(($for_count % $batch_count) == 0){
                    $pipe->exec();
                    $pipe = self::$redisCache->multi(self::PIPELINE);
                }
                $for_count++;
            }
            if(($for_count % $batch_count) != 0){
                self::$redisCache->exec();
            }
        }
    }

    /**
     * 获取hash中的数据
     * @param $key_pre
     * @param array $keys
     * @param int $batch_count
     */
    public function getHashList($key_pre,$keys){
        $pipe = self::$redisCache->multi(self::PIPELINE);
        foreach ($keys as $item){
            $pipe->hGetAll("{$key_pre}{$item}");
        }
        return self::$redisCache->exec();
    }


    /**
     * 增加经纬度数据
     * @param $data
     * @param int $batch_count
     */
    public function geoMultiAdd($key,$data,$batch_count=100){
        if(count($data) == count($data,COUNT_RECURSIVE)){ //判断是否为一维数组
            $this->geoAdd($key, ... $data);
        }else{
            $for_count = 1;
            $temp_data = [];
            foreach ($data as $item){
                $temp_data = array_merge($temp_data,array_values($item));
                if(($for_count % $batch_count) == 0){
                    $this->geoAdd($key, ... $temp_data);
                    $temp_data= [];
                }

                $for_count++;
            }

            if($temp_data){
                $this->geoAdd($key, ... $temp_data);
            }
        }
    }

}