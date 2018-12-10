<?php
namespace App\Yszc\AdminBundle\DependencyInjection;

class RedisService {
    protected $redis;

    function __construct($redis)
    {
	    $redis->auth('ys123456');
        $this->redis = $redis;
    }


    /**
     * 设置缓存值
     * @param string $key
     * @param string $value
     * @param int $expire_time  过期时间 ，为空则默认为永久有效
     * @return boolean
     */
    public function cache($key, $value=NULL, $expire_time = 0)
    {
        $ret = NULL;
        if(!empty($value)){
            $ret = $this->redis->set($key, serialize($value));
            if($expire_time){
                $this->redis->expire($key, $expire_time);
            }
        }else{
            $ret = unserialize($this->redis->get($key));
        }
        return $ret;
    }

    /**
     * 删除缓存
     * @param string $key
     * @return boolean
     */
    public  function delCache($key){
        $ret = $this->redis->del($key);
        return $ret;
    }

    /**
     * @return redis对象
     */
    public function getRedis(){
        return $this->redis;
    }

	public function __call($method, $arguments){
		return call_user_func_array(array($this->redis, $method), $arguments);
	}

}