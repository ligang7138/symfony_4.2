<?php
namespace App\Yszc\AdminBundle\DependencyInjection;

class AppService {
	private $service = '';

	public function __construct($service = NULL) {
		$this->service = $service;
	}

    public function getService($service_name){
        return $this->createService($service_name);
    }

    private function createService($name){
        if (empty($this->pool[$name])) {
            $class = $this->getClassName($name);
            $this->pool[$name] = new  $class($this->service->get('doctrine'),$this->service);
        }

        return $this->pool[$name];
    }

    private function getClassName($name){
        if (strpos($name, ':') > 0) {
            list($namespace, $name) = explode(':', $name, 2);
            $namespace = '\\'.$namespace.'\\Service';
        }else{
            $namespace = '\\AdminBundle\\Services';
        }
        if(2 != count(explode('.', $name))){
            return false;
        }
        list($module, $className) = explode('.', $name);

        return $namespace.'\\'.$module.'\\Impl\\'.$className.'Impl';
    }

}