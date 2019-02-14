<?php
namespace App\AdminBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Events;
use App\AdminBundle\Common\CommonFunction;

class ApiDoctrineSubscriber implements EventSubscriber
{
    protected $service;
    protected $redis;

    public function __construct($service,$redis)
    {
        $this->service = $service;
        $this->redis = $redis;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::postUpdate,
            Events::postRemove,
            Events::onClear,
            Events::postPersist
        ];
    }


    public function onClear()
    {
    }

    /**
     * @param EventArgs $args
     */
    public function postUpdate(EventArgs $args)
    {
        $this->handleEvent($args);
    }

    /**
     * @param EventArgs $args
     */
    public function postPersist(EventArgs $args)
    {
        $this->handleEvent($args);
    }
    /**
     * @param EventArgs $args
     */
    public function postRemove(EventArgs $args)
    {
        $this->handleEvent($args);
    }

    /**
     * 处理事件
     * @param EventArgs $args
     */
    protected function handleEvent(EventArgs $args){
        $table_name = $args->getObjectManager()->getClassMetadata(get_class($args->getObject()))->getTableName();
        $tables = $this->getParameter('admin_bundle')['api_cache_tables'];

        if(isset($tables[$table_name])){
            $object = $args->getObject();
            $cache_keys = $tables[$table_name];
            if(is_array($cache_keys)){
                foreach ($cache_keys as $cache_key){
                    if(preg_match_all('#{([a-z_^:]+)}+#',$cache_key,$matches)){
                        $replace_arr = [];
                        foreach ($matches[0] as $key => $item){
                            $replace_arr[$item] = $this->getObjectValueByName($object,$matches[1][$key]);
                        }
                        $new_cache_key = strtr($cache_key,$replace_arr);
                        $this->redis->delCache($new_cache_key);
                    }else if(is_array($cache_key)){

                    }else{
                        $this->redis->delCache($cache_key);
                    }

                }
            }
        }
    }

    protected function getParameter($name)
    {
        return $this->service->getParameter($name);
    }

    protected function handleService($object,$service_params){
        $params =  $this->service->getParameter('admin_bundle');
        $request_params = [];

        if(isset($service_params['match_params'])){
            $is_match = true;
            foreach ($service_params['match_params'] as $key => $item){
                $value = $this->getObjectValueByName($object,$key);
                if(is_array($item) && !in_array($value,$item)){
                    $is_match = false;
                }else if($value != $item){
                    $is_match = false;
                }
            }

            if($is_match == false){
                return false;
            }
        }

        if(isset($service_params['params'])){

            foreach ($service_params['params'] as $key=>$item){
                if(is_int($key)){
                    $request_params[$item] = $this->getObjectValueByName($object,$item);
                }else{
                    $request_params[$key] = $item;
                }
            }
        }

        return CommonFunction::getEsbInterface($params['ESB'], $service_params['service'], $service_params['interface'], $request_params);
    }


    /**
     * 根据字段名返回对象的值
     * @param $object
     * @param $field_name
     * @return mixed
     */
    protected function getObjectValueByName($object,$field_name){
        $method_name = 'get'.ucwords(str_replace('_','',$field_name));
        if(method_exists($object,$method_name)){
            return $object->$method_name();
        }else{
            return false;
        }

    }


}