<?php

namespace App\Yszc\AdminBundle\Controller;
use Doctrine\Common\Util\Debug;
use Symfony\Component\HttpFoundation\Request;
use App\Yszc\AdminBundle\Common\CommonFunction;
use App\Yszc\AdminBundle\Controller\CommonController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class CityController extends CommonController
{
    /**
     * 市列表
     * @Route("/admin/city_list.html")
     */
    public function cityList(Request $request){
	  $params               = $request->get("pid");
	  $select               = $request->get("select");
	  list($code,$province) = explode("_", $params);
	  $cityService          = $this->createService('City.CityService');
	  $city                 = $cityService->cityList($province);
	  $html                 = '<option value="">--请选择开户市--</option>';
	  foreach ($city as $key => $value) {
		if($select == $value){
		    $html .= '<option value="'.$key.'_'.$value.'" selected>'.$value.'</option>';
		}else{
		    $html .= '<option value="'.$key.'_'.$value.'">'.$value.'</option>';
		}

	  }
	  echo $html;exit();
    }

    /**
     * 县列表
     * @Route("/admin/area_list.html")
     */
    public function areaList(Request $request){
	  $params           = $request->get("cid");
	  $select           = $request->get("select");
	  list($code,$city) = explode("_", $params);
	  $cityService      = $this->createService('City.CityService');
	  $area             = $cityService->areaList($city);
	  $html             = '<option value="">--请选择开户县--</option>';
	  foreach ($area as $key => $value) {
		if($select == $value["area_name"]){
		    $html .= '<option value="'.$value["area_code"].'_'.$value["area_name"].'" selected>'.$value["area_name"].'</option>';
		}else{
		    $html .= '<option value="'.$value["area_code"].'_'.$value["area_name"].'">'.$value["area_name"].'</option>';
		}
	  }
	  echo $html;exit();
    }

    /**
     * 县列表
     * @Route("/admin/get_city_list.html")
     */
    public function getCityList(Request $request){
	  $c_id        = $request->get("c_id");
	  $select      = $request->get("select");
	  $cityService = $this->createService('City.CityService');
	  $city        = $cityService->getCityList($c_id);
	  $html = '';
	  foreach ($city as $key => $value) {
		if($select == $value['city_key']){
		    $html .= '<option value="'.$value['city_key'].'" selected="selected">'.$value['city_name'].'</option>';
		}else{
		    $html .= '<option value="'.$value['city_key'].'">'.$value['city_name'].'</option>';
		}
	  }
	  echo $html;exit();
    }
}
