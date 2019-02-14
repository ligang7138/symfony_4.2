<?php

/**
 * Created by GroupTour.
 * User: ydmx_lei
 * Time: 2016/12/21 16:40
 */
namespace App\AdminBundle\Twig;

use App\AdminBundle\Constant\MerchantConstant;

/**
 * 扩展函数
 * exmple: {{test|gfut}}  多个参数 {{test|gfut('params1','params2')}}
 */
class AdminExtension extends \Twig_Extension
{
    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('gfut', array($this, 'asseticFilter')),
            new \Twig_SimpleFilter('urldecode', array($this, 'urldecode')),
            new \Twig_SimpleFilter('my_dump', array($this, 'var_export')),
            new \Twig_SimpleFilter('inarray', array($this, 'inarray')),
            new \Twig_SimpleFilter('getCharByString', array($this, 'getCharByString')),
            new \Twig_SimpleFilter('diffBetweenTwoDays', array(\App\AdminBundle\Common\CommonFunction::class, 'diffBetweenTwoDays')),
	        new \Twig_SimpleFilter('get2dateDiff', array($this, 'get2dateDiff')),
	        new \Twig_SimpleFilter('valToStrByMap', array($this, 'valToStrByMap')),
            new \Twig_SimpleFilter('valInArray', array($this, 'valInArray')),
            new \Twig_SimpleFilter('arrayInArray', array($this, 'arrayInArray')),
            new \Twig_SimpleFilter('getValueByKey', array($this, 'getValueByKey')),
	        new \Twig_SimpleFilter('gaddrbyip', array(\App\AdminBundle\Common\CommonFunction::class, 'gaddrbyip')),
	        new \Twig_SimpleFilter('getSexByIdCard', array(\App\AdminBundle\Common\CommonFunction::class, 'getSexByIdCard')),
	        new \Twig_SimpleFilter('getAgeByIdCard', array(\App\AdminBundle\Common\CommonFunction::class, 'getAgeByIdCard')),
	        new \Twig_SimpleFilter('getBirthDayByIdCard', array(\App\AdminBundle\Common\CommonFunction::class, 'getBirthDayByIdCard')),
	        new \Twig_SimpleFilter('getValueByJson', array($this, 'getValueByJson')),
	        new \Twig_SimpleFilter('getTimeDiff', array(\App\AdminBundle\Common\CommonFunction::class, 'get_time_diff')),
        );
    }

    public function asseticFilter($url){
        if(false !== strpos($url,'?')){
            return $url;
        }else if (($timestamp = @filemtime('.'.$url)) > 0) {
            return "$url?v=$timestamp";
        }else{
            return $url;
        }
    }
    
    public function inarray($str,$str2){
        return in_array($str,explode(',',$str2));
    }
    
    public function getCharByString($str,$index){
        return $str{$index};
    }

    public function get2dateDiff($start_time){
	    $second1 = time();
	    $second2= strtotime($start_time);
	    if ($second1 < $second2) {
		    return 0;
	    }
	    return intval($second1 - $second2);
    }

	/**
	 * 从json串中取值
	 * @param $json
	 * @param $key
	 * @return mixed
	 */
	public function getValueByJson($json,$key){
    	$arr = json_decode($json,true);
    	return $arr[$key];
	}
    /**
     * 根据map将值转换为对应字符串
     * @param $val
     * @param $maps
     * @param string $str_splice
     */
    public function valToStrByMap($val,$maps,$str_splice=','){
        $result = null;
        if(is_string($val)){
            if(strpos($val,$str_splice) !== false){
                $val = explode($str_splice,$val);
            }
        }

        if(is_array($val)){
            foreach ($val as $item){
                $result[] = $maps[$item];
            }
            if(is_array($result)){
                $result = implode($str_splice,$result);
            }else{
                $result = '';
            }

        }else{
            $result = $maps[$val];
        }

        return $result;
    }

    /**
     * 根据map将值转换为对应字符串
     * @param $val
     * @param $maps
     * @param string $str_splice
     */
    public function valInArray($maps,$val,$str_splice=','){
        $result = null;
        if(!is_array($maps)){
            $maps = explode($str_splice,$maps);
        }
        return in_array($val,$maps);
    }

	/**
	 * 此方法只适用于常量类
	 * @param $key
	 * @param $const
	 * @param $keyName
	 * @return mixed|string
	 * @throws \ReflectionException
	 */
    public function getValueByKey($key,$const,$keyName){
    	$const = ucfirst($const.'Constant');
    	// 获取常量类的反射对象
    	$reflection = new \ReflectionClass("App\AdminBundle\Constant\\$const");

	    $method = 'get' . ucfirst($keyName);
	    // 判断类中是否有此方法
	    if($reflection->hasMethod($method)){
	    	return $reflection->getMethod($method)->invoke($reflection->newInstance(),$key);
	    }else{
	    	return '';
	    }
    }
	/**
	 * 多角色用户判断
	 * @param $maps
	 * @param $val
	 * @param string $str_splice
	 * @return bool
	 */
    public function arrayInArray($maps,$val,$str_splice=','){
	    $result = null;
	    if(!is_array($maps)){
		    $maps = explode($str_splice,$maps);
	    }
	    if(!is_array($val)){
	    	$val = (array)$val;
	    }

	    return !empty(array_intersect($maps,$val));
    }
    
    public function getName()
    {
        return 'admin_extension';
    }
}