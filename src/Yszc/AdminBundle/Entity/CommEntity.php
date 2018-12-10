<?php

namespace App\Yszc\AdminBundle\Entity;
use App\Yszc\AdminBundle\Common\CommonFunction;
/**
 * 公共实体数据转换类
 */
abstract class CommEntity{
    private function changeType(){
	  $reflect = new \ReflectionClass($this);
	  $properties = $reflect->getDefaultProperties();
	  $array = [];
	  foreach($properties as $key=>$propertie){
	      $mothd = 'get'.ucfirst($key);
		$array[CommonFunction::uncamelize($key)]=$this->$mothd();
	  }
	  return $array;
    }
    public function getArrayResult(){
	  return $this->changeType();
    }

    public function setObject($oldObject,$newObject){
	    $reflect = new \ReflectionClass($oldObject);
	    $properties = $reflect->getDefaultProperties();
	    foreach($properties as $key=>$propertie){
		    $getMothd = 'get'.ucfirst($key);
		    $setMothd = 'set'.ucfirst($key);
		    $newObject->$setMothd($oldObject->$getMothd());
	    }
	    return $newObject;
    }

    public function getJsonResult(){
	  $array = $this->changeType();
	  return json_encode($array);
    }
}
