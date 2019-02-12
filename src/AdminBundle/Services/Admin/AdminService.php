<?php
/**
 * Created by AdminBundle.
 * User: ydmx_lei
 * Date: 2016/11/30
 * Time: 14:00
 */

namespace AdminBundle\Services\Admin;


interface  AdminService
{
    //获取单个或多个模块数据
    public function getModel($mId);
    
    //获取所有模块并分页
    public function getModles($mCode = NULL);

    //获取权限
    public function getRoles($role_ids);

    //获取操作事件
    public function getEvents($e_ids);

    //获取对应权限的所有菜单项
    public function getMenus($role_ids);

    //获取子菜单项
    public function getChildMenu($mid,$bool=true);

    //构建菜单
    public function buildMenus($menus = [],$root = 0);
    
    //获取菜单和事件关联信息
    public function getEventAndMenu($menu_id);

    //获取所有模块及对应的事件
    public function getAllModlesAndEvents($mCode = NULL);

    //根据控制器及方法名获取对应标识ID
    public function getControllerAndMethodById($controller=NULL,$method=NULL);

    //获取当前用户ca
    public function getControllerAndMethodByCurrUser($e_ids);
    
    //获取所有后台用户
    public function getAllUsers($conditions = NULL);

    //获取当前用户消息列表
	public function getMessageList($conditions = NULL);

}