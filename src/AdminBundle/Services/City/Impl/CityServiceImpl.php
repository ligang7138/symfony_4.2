<?php
/**
 * Created AdminBundle
 * User: yangjun
 * Date: 2017/7/5
 * Time: 13:50
 */

namespace App\AdminBundle\Services\City\Impl;
use App\AdminBundle\Services\Service;
use App\AdminBundle\Services\City\CityService;

class CityServiceImpl extends Service implements CityService
{
    public function provinceList()
    {
        return $this->db->fetchAll("select area_code,province from my_bank_areacode where level = '省级' group by province order by area_code asc");
    }
    
    public function cityList($p_id)
    {
        return $this->db->fetchAll("select area_code,city from my_bank_areacode where province = ? group by city order by area_code asc");
    }

    //数据有重复
    public function areaList($c_id)
    {
        return $this->db->fetchAll("select area_code,area_name from my_bank_areacode where city = ? order by area_code asc");
    }

    public function getCityName($c_id)
    {
        return $this->db->fetchColumn("select city_name from my_city where city_key = '{$c_id}'");
    }

    public function getCityList($c_id = "CHINA")
    {
        return $this->db->fetchAll("select city_key,city_name from my_city where city_pid in(select city_id from my_city where city_key = ?) order by city_key asc",[$c_id]);
    }

    public function getParentCode($code)
    {
        return $this->db->fetchColumn("SELECT pc.city_key from my_city pc 
                                            LEFT JOIN my_city c on pc.city_id = c.city_pid
                                            where c.city_key = ?",[$code]);
    }
}