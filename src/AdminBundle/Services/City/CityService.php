<?php
namespace App\AdminBundle\Services\City;
/**
 * CityService 地址服务
 * @author [yangjun] <[cooljun@126.com]>
 * @date 2017-06-27
 */
interface  CityService
{
    //获取银行所在省份
    public function provinceList();

    //获取银行所在市
    public function cityList($p_id);

    //获取银行所在县
    public function areaList($c_id);

    //获取名称
    public function getCityName($c_id);

    //获取所在地区信息
    public function getCityList($c_id = "CHINA");

    //获取父级city_key
    public function getParentCode($code);
}