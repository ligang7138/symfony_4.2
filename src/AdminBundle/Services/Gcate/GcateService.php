<?php
/**
 * Created by AdminBundle.
 * User: ydmx_lei
 * Date: 2018/06/20
 * Time: 14:00
 */

namespace App\AdminBundle\Services\Gcate;


interface  GcateService
{
	//获取顶级分类
	public function getAllGcates($conditions = NULL);

	// 获取所有二级分类信息
    public function getLowerLevelInfo($gc_node);


}