<?php
/**
 * Created AdminBundle
 * User: ydmx_lei
 * Date: 2016/11/30
 * Time: 14:05
 */

namespace App\Yszc\AdminBundle\Services\Admin\Impl;
use App\Yszc\AdminBundle\Services\Service;
use App\Yszc\AdminBundle\Services\Admin\AdminService;
use Doctrine\DBAL\Driver\Connection;

class AdminServiceImpl extends Service implements AdminService
{
    public function getRoles($role_ids=NULL,$fag=true)
    {
        if(isset($role_ids)){
            $query = $this->doctrine->getManager()->createQuery("SELECT r FROM AdminBundle:AdminRoles r  WHERE r.rId in($role_ids)")->getResult();
        }else{
            if($fag){
                $query = $this->doctrine->getManager()->createQuery("SELECT r FROM AdminBundle:AdminRoles r")->getResult();
            }else{
                $query = $this->getList('SELECT * FROM admin_roles',[]);
            }  
        }
        

        return  $query;
    }

    public function getEvents($e_ids=0)
    {
        $query = $this->doctrine->getManager()->createQuery("SELECT e FROM AdminBundle:EventActions e WHERE e.eId in($e_ids)");

        return  $query->getResult();
    }

    public function getMenus($m_ids=NULL,$status=1)
    {
        if($m_ids){
            $sql = "SELECT m FROM AdminBundle:AdminMenus m WHERE  m.menuId in($m_ids)";
            if(1==$status){
                $sql = "SELECT m FROM AdminBundle:AdminMenus m WHERE m.menuStatus = 1 and m.menuId in($m_ids)";
            }
            $query = $this->doctrine->getManager()->createQuery($sql);
        }else{
            $sql = "SELECT m FROM AdminBundle:AdminMenus m";
            if($status==1){
                $sql = "SELECT m FROM AdminBundle:AdminMenus m WHERE m.menuStatus = 1";
            }
            $query = $this->doctrine->getManager()->createQuery($sql);
        }
        return  $query->getResult();
    }

    public function getChildMenu($mid,$bool=true)
    {
        if($bool){
            $query = $this->doctrine->getManager()
            ->createQuery('SELECT m FROM AdminBundle:AdminMenus m WHERE m.menuChildId = :menuChildId ORDER BY m.menuId')->setParameter('menuChildId', $mid)->getResult();
        }else{
            $query = $this->doctrine->getRepository('AdminBundle:AdminMenus')->findOneBy(['menuChildId'=>$mid]);
        }
        
        return  $query;
    }


    public function buildMenus($menus=[],$root=0){
        $tree=array();
        $packData=array();
        foreach ($menus as  $menu) {
            $packData[$menu->getMenuId()] = ['menuId'=>$menu->getMenuId(),'menuStatus'=>$menu->getMenuStatus(),'menuChildId'=>$menu->getMenuChildId(),'menuName'=>$menu->getMenuName(),'menuRoute'=>$menu->getMenuRouteName(),'menuIcon'=>$menu->getMenuIcon()];
        }
        foreach ($packData as $key =>$val){
            if($val['menuChildId']==$root){
                $tree[]=&$packData[$key];
            }else{
                $packData[$val['menuChildId']]['items'][]=&$packData[$key];
            }
        }
        return $tree;
    }

    public function getModles($conditions = NULL){
        $query = NULL;
        $am = $this->doctrine->getRepository('AdminBundle:AdminModles');
        if(!empty($conditions) && is_string($conditions)){
            $query = $am->findOneBy(['mCode'=>$conditions]);
        }else {
            if(empty($conditions['mname'])){
                $conditions['mname'] = '';
            }

            $limit = '';
            if(!empty($conditions['limit'])){
                $limit = ' limit ' . $conditions['limit']['start']. ',' .$conditions['limit']['end'];
            }
            $query = $this->getList("select * from (SELECT m_id,m_name,m_code,m_add_time,'' as e_id,'' as e_name,'' as e_code,'' as e_add_time FROM admin_modles where m_id not in ( SELECT m.m_id  FROM admin_modles m ,event_actions e where m.m_id=e.m_id group by m.m_id) union all SELECT m.m_id,m.m_name,m.m_code,m.m_add_time,e.e_id,e.e_name,e.e_code,e.e_add_time FROM admin_modles m , event_actions e where m.m_id=e.m_id) temp  WHERE m_name like ? and e_name like ? order by e_add_time desc " . $limit,[$conditions['mname'] . '%',$conditions['ename'] . '%']);
        }
        return $query;
    }
    
    public function getModel($mId=null){
        $am = $this->doctrine->getRepository('AdminBundle:AdminModles');
        if(empty($mId)){
            $result = $am->findAll();
        }else{
            $result = $am->findOneBy(['mId'=>$mId]);
        }
        
        return $result;
    }
    
    public function getEventAndMenu($menu_cid=0){
        $event_list = [];
        if(!empty($menu_cid)){
            $event_list = $this->doctrine->getConnection()->fetchAll('SELECT e.menu_id,e.e_id,m.menu_name,e.e_code,e.e_name FROM event_actions e left join admin_menus m on e.menu_id=m.menu_id where m.menu_child_id=?',[$menu_cid]);
        }
        return $event_list;
    }

    public function getAllModlesAndEvents($mCode = NULL){
        $result = NULL;
        if(!empty($mCode)){
            $result = $this->doctrine->getRepository('AdminBundle:AdminModles')->findOneBy(['mCode'=>$mCode]);
        }else {
            $event_list = $this->doctrine->getConnection()->fetchAll('SELECT e.menu_id,e.e_id,m.menu_name,e.e_code,e.e_name FROM event_actions e left join admin_menus m on e.menu_id=m.menu_id');
            foreach($event_list as $mevent){
                $result[$mevent['menu_name']][] = ['menu_id'=>$mevent['menu_id'],'e_id'=>$mevent['e_id'],'e_name'=>$mevent['e_name'],'e_code'=>$mevent['e_code']];
            }
        }
        return $result;
    }

    public function getControllerAndMethodById($controller=NULL, $method=NULL)
    {
        $db = $this->doctrine->getConnection();
        if(empty($controller) && empty($method)){
            $result = $db->fetchAll('select * from event_actions e left join admin_modles m on e.m_id=m.m_id');
        }else if(!empty($controller) && empty($method)){
            $result = $db->fetchAll('select * from event_actions e left join admin_modles m on e.m_id=m.m_id where m.m_code=?',[$controller]);
        }else{
            $result = $db->fetchAssoc('select * from event_actions e left join admin_modles m on e.m_id=m.m_id where e.e_code=? and m.m_code=?',[$method,$controller]);
        }
        return  $result;
    }

    public function getControllerAndMethodByCurrUser($e_ids,$type=false)
    {
        $result = NULL;
        if(!empty($e_ids)){
            $filed = '*';
            if($type){
                $filed = "concat(m.m_code,'::',e.e_code) as controller";
            }
            $result = $this->doctrine->getConnection()->fetchAll("select $filed from event_actions e left join admin_modles m on e.m_id=m.m_id where e.e_id in ($e_ids)");
        }
        return $result;

    }

    /**
     * 菜单名称检测
     */
    public function checkMenuName($name,$mid = null)
    {
       $adminMenu    = $this->doctrine->getRepository('AdminBundle:AdminMenus')->findOneBy(["menuName"=>$name]);
       if($mid&&$adminMenu){
            if($adminMenu->getMenuId()==$mid){
                return false;
            }
       }
       return $adminMenu;
    }
    
    public function getAllUsers($conditions = NULL){
        if(empty($conditions['mname'])){
            $conditions['mname'] = '';
        }
        
        $limit = '';
        if(!empty($conditions['limit'])){
            $limit = ' limit ' . $conditions['limit']['start']. ',' .$conditions['limit']['end'];
        }

        $query = $this->getList('SELECT au.a_id as aid,au.*,aui.* FROM admin_users au LEFT JOIN admin_user_info aui on au.a_id = aui.a_id  WHERE au.a_name like ? ' . $limit,[$conditions['mname'] . '%']);
        return  $query;
    }

	public function getMessageList($conditions = NULL){

		$where = ' where um.u_code = ? and msg_send_status = 2 ';
		if(trim($conditions['m_start'])){
			$where .= " and msg_add_time>='$conditions[m_start] 00:00:00'";
		}

		if(trim($conditions['m_end'])){
			$where .= " and msg_add_time<='$conditions[m_end] 23:59:59'";
		}

		$limit = '';
		if(!empty($conditions['limit'])){
			$limit = ' limit ' . $conditions['limit']['start']. ',' .$conditions['limit']['end'];
		}
		$query = $this->getList('SELECT m.msg_id,m.admin_id,m.msg_title,m.msg_content,m.msg_add_time,um.read_status FROM ys_message m inner join ys_user_message um on m.msg_id = um.msg_id ' . $where . ' order by m.msg_id desc ' . $limit,[$conditions['admin_id']]);
		return  $query;
	}

    public function lastVersion($type,$app_type=3){
        $db = $this->doctrine->getConnection('fenqi');
        $result = $db->fetchAssoc('select id,title,update_time,app_url,is_force,info,version_no,filesize,type from my_release where type=? and app_type = ? order by version_no desc', [$type,$app_type]);
        return  $result;
    }

	/**
	 * @see UsersDao::getUserInfoByCode()
	 */
	public function getUserDeviceInfo(string $uCode)
	{
		$connect = $this->doctrine->getManager('fenqi')->getConnection();
		$sql = 'select id,brands,system_model,system_version,device_id,phone,create_date,last_update_date,u_code from my_user_data_device  where u_code=?';
		$result = $connect->fetchAll($sql,[$uCode]);


		if($result){
			foreach ($result as $item){
				$devices[$item['device_id']] = $item;
			}
		}else {
			$devices = false;
		}
		return $devices;
	}
	private function filterPhone($str){
		return strtr($str,['+86'=>'',' '=>'']);
	}

	/**
	 * @return \Doctrine\DBAL\Connection mixed
	 */
	public function getFeiqiConnect(){
		return $this->doctrine->getManager('fenqi')->getConnection();
	}
    /**
     * @see UsersDao::savePhoneMessage()
     */
    public function savePhoneMessage(array $data){
        if(!empty($data['device_info'])){
            $device_info = json_decode($data['device_info'],true);
            $device_id = $device_info['IMEI'];
        }else {
            return false;
        }
	    $connect = $this->getFeiqiConnect();
        $device_list = $this->getUserDeviceInfo($data['u_code']);
        $current_time = date('Y-m-d H:i:s');

        if(!isset($device_list[$device_id])){
            /*$insert_device_info = [];
            $insert_device_info['brands'] = $device_info['Brands'];
            $insert_device_info['system_model'] = $device_info['SystemModel'];
            $insert_device_info['system_version'] = $device_info['Brands'];
            $insert_device_info['device_id'] = $device_info['IMEI'];
            $insert_device_info['u_code'] = $data['u_code'];
            $insert_device_info['create_date'] = $current_time;*/
            $phone = $this->filterPhone($device_info['phone']);
			$sql = "insert into my_user_data_device (`brands`,`system_model`,`system_version`,`device_id`,`phone`,`u_code`,`create_date`) values ('{$device_info['Brands']}','{$device_info['SystemModel']}','{$device_info['Brands']}','{$device_info['IMEI']}','{$phone}','{$data['u_code']}','{$current_time}')";
            //位置信息为空时设置时间
            if(empty($data['position_info'])){
	            $sql = "insert into my_user_data_device (`brands`,`system_model`,`system_version`,`device_id`,`phone`,`u_code`,`last_update_date`,`create_date`) values ('{$device_info['Brands']}','{$device_info['SystemModel']}','{$device_info['Brands']}','{$device_info['IMEI']}','{$phone}','{$data['u_code']}','{$current_time}','{$current_time}')";
//                $insert_device_info['last_update_date'] = $current_time;
            }

            $connect->exec($sql);
            /*self::$db->insertInto('my_user_data_device')
                ->values($insert_device_info)->exec();*/

        }else{
            //位置信息为空时更新时间
            if(empty($data['position_info'])){
                $last_update_date = $device_list[$device_id]['last_update_date'];
                $sql = "update  my_user_data_device set last_update_date='{$current_time}' where u_code='{$data['u_code']}' and device_id='{$device_id}'";
                /*self::$db->execute('update  my_user_data_device set last_update_date=? where u_code=? and device_id=?', [$current_time,$data['u_code'], $device_id]);*/
	            $connect->executeUpdate($sql);
            }
        }
        if(!empty($data['sms_info'])){
            $this->insertUserSmsInfo($data['u_code'],$device_id,$data['sms_info'],$last_update_date);
        }

        if(!empty($data['position_info'])){
            $this->insertUserPositionInfo($data['u_code'],$device_id,$data['position_info']);

	        $connect->exec($sql);
        }

        if(!empty($data['record_info'])){
            $this->insertUserCallInfo($data['u_code'],$device_id,$data['record_info'],$last_update_date);
        }

        if(!empty($data['app_info'])){
            $this->insertUserAppInfo($data['u_code'],$device_id,$data['app_info']);
        }

        if(!empty($data['relation_info'])){
            $this->insertUserRelationInfo($data['u_code'],$device_id,$data['relation_info']);
        }

        return true;
    }

    public function insertUserSmsInfo($u_code,$device_id,$sms_info,$last_update_date,$batch_num = 1000){
        $sms_info = json_decode($sms_info,true);
        $current_time = date('Y-m-d H:i:s');
        if($sms_info){
            $insert_data = [];
            $for_count = 0; //循环记数
	        /** @var \Doctrine\DBAL\Connection $connect */
	        $connect = $this->doctrine->getManager('fenqi')->getConnection();
	        $sql = "insert into my_user_data_sms (`device_sms_id`,`body`,`sms_date`,`sms_name`,`sms_num`,`is_read`,`thread_id`,`type`,`create_date`,`u_code`,`device_id`) values ";
	        $rowValue = "";
            foreach ($sms_info as $item){
                if($item['type'] == 3){
                    continue;
                }
                if($item['date'] > $last_update_date){
                    $row = [];
                    $row['device_sms_id'] = $item['_id'];
                    //短信内容中存在4字节内容，通过json_encode方法将部分内容转为\u****形式
                    $row['body'] = trim(json_encode($item['body'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),'""');
                    //$row['body'] = preg_replace('/[\x{0000}]/iu','',$item['body']); 使用正则替换
                    //$row['body'] = Functions::unicode_decode($item['body']);
                    $row['sms_date'] = $item['date'];
                    $row['sms_name'] = $item['name'];
                    $row['sms_num'] = $item['num'];
                    $row['is_read'] = $item['read'];
                    $row['thread_id'] = $item['thread_id'];
                    $row['type'] = $item['type'];
                    $row['create_date'] = $current_time;
                    $row['u_code'] = $u_code;
                    $row['device_id'] = $device_id;
                    $insert_data[] = $row;
                    $for_count ++ ;
	                $rowValue .= "('{$row['device_sms_id']}','{$row['body']}','{$row['sms_date']}','{$row['sms_name']}','{$row['sms_num']}','{$row['is_read']}','{$row['thread_id']}','{$row['type']}','{$row['create_date']}','{$row['u_code']}','{$row['device_id']}'),";
                    if(($for_count % $batch_num) == 0){
                        /*self::$db->insertInto('my_user_data_sms')
                            ->batchValues($insert_data)->exec();*/
	                    $sql .= trim($rowValue,',');
	                    $connect->exec($sql);
//                        $insert_data = [];
	                    $rowValue = '';
                    }
                }
            }

            if($insert_data){
	            $connect->exec($sql.trim($rowValue,','));
                /*self::$db->insertInto('my_user_data_sms')
                    ->batchValues($insert_data)->exec();*/
            }
        }
    }



    public function insertUserPositionInfo($u_code,$device_id,$position_info,$batch_num = 1000){
        $position_info = json_decode($position_info,true);
        $current_time = date('Y-m-d H:i:s');
        if($position_info){
            $insert_data = [];
            $for_count = 1; //循环记数
	        $connect = $this->getFeiqiConnect();
	        $sql = "insert into my_user_data_position (`addr`,`latitude`,`lontitude`,`log_time`,`create_date`,`u_code`,`device_id`) values ";
	        $rowValue = '';
            foreach ($position_info as $item){
                $row = [];
                $row['addr'] = $item['addr'];
                $row['latitude'] = floatval($item['latitude']);
                $row['lontitude'] = floatval($item['lontitude']);
                $row['log_time'] = $item['time'];
                $row['create_date'] = $current_time;
                $row['u_code'] = $u_code;
                $row['device_id'] = $device_id;
                $insert_data[] = $row;
                $for_count ++ ;
                $rowValue .= "('{$row['addr']}','{$row['latitude']},'{$row['lontitude']}','{$row['log_time']}','{$row['create_date']}','{$row['u_code']}','{$row['device_id']}'),";
                if(($for_count % $batch_num) == 0){
                	$sql .= trim($rowValue,',');
                	$connect->exec($sql);
                    /*self::$db->insertInto('my_user_data_position')
                        ->batchValues($insert_data)->exec();*/
//                    $insert_data = [];
	                $rowValue = '';
                }
            }

            if($insert_data){
	            $connect->exec($sql.trim($rowValue,','));
                /*self::$db->insertInto('my_user_data_position')
                    ->batchValues($insert_data)->exec();*/
            }
        }
    }


    public function insertUserCallInfo($u_code,$device_id,$call_info,$last_update_date,$batch_num = 1000){
        $call_info = json_decode($call_info,true);
        $current_time = date('Y-m-d H:i:s');
        if($call_info){
            $insert_data = [];
            $for_count = 1; //循环记数
	        /** @var \Doctrine\DBAL\Connection $connect */
	        $connect = $this->doctrine->getManager('fenqi')->getConnection();
	        $sql = "insert into my_user_data_call (`call_type`,`call_duration`,`call_date`,`call_name`,`call_num`,`create_date`,`u_code`,`device_id`) values ";
	        $rowValue = "";
            foreach ($call_info as $item){
                if($item['date'] > $last_update_date){
                    $row = [];
                    $row['call_type'] = $item['callType'];
                    $row['call_duration'] = $item['duration'];
                    $row['call_date'] = $item['date'];
                    $row['call_name'] = $item['name'];
                    $row['call_num'] = $item['number'];
                    $row['create_date'] = $current_time;
                    $row['u_code'] = $u_code;
                    $row['device_id'] = $device_id;
                    $insert_data[] = $row;
                    $for_count ++ ;
	                $rowValue .= "('{$row['call_type']},{$row['call_duration']},{$row['call_date']},{$row['call_name']},{$row['call_num']},{$row['create_date']},{$row['u_code']}',{$row['device_id']}'),";

                    if(($for_count % $batch_num) == 0){
                        /*self::$db->insertInto('my_user_data_call')
                            ->batchValues($insert_data)->exec();
                        $insert_data = [];*/
	                    $sql .= trim($rowValue,',');
	                    $connect->exec($sql);
	                    $rowValue = '';
                    }
                }
            }

            if($insert_data){
	            $connect->exec($sql.trim($rowValue,','));
                /*self::$db->insertInto('my_user_data_call')
                    ->batchValues($insert_data)->exec();*/
            }
        }
    }

    public function insertUserAppInfo($u_code,$device_id,$app_info,$batch_num = 1000){
        $app_info = json_decode($app_info,true);
        $current_time = date('Y-m-d H:i:s');
        if($app_info){
	        /** @var \Doctrine\DBAL\Connection $connect */
	        $connect = $this->doctrine->getManager('fenqi')->getConnection();
	        $sql = "insert into my_user_data_app (`app_name`,`package_name`,`create_date`,`u_code`,`device_id`) values ";
	        $rowValue = "";
            foreach ($app_info as $item){
                $row = [];
                $row['app_name'] = $item['appName'];
                $row['package_name'] = $item['appPackageName'];
                $row['create_date'] = $current_time;
                $row['u_code'] = $u_code;
                $row['device_id'] = $device_id;
	            $rowValue .= "('{$row['app_name']}','{$row['package_name']}','{$row['create_date']}','{$row['u_code']}','{$row['device_id']}'),";
                /*self::$db->replaceInto('my_user_data_app')
                    ->values($row)->exec();*/
            }

	        $connect->exec($sql.trim($rowValue,','));
        }
    }

    public function insertUserRelationInfo($u_code,$device_id,$relation_info,$batch_num = 1000){
        $relation_info = json_decode($relation_info,true);
        $current_time = date('Y-m-d H:i:s');
        if($relation_info){
	        /** @var \Doctrine\DBAL\Connection $connect */
	        $connect = $this->doctrine->getManager('fenqi')->getConnection();
	        $sql = "insert into my_user_data_relation (`rel_name`,`rel_number`,`create_date`,`u_code`,`device_id`) values ";
	        $rowValue = "";
            foreach ($relation_info as $item){
                $row = [];
                $row['rel_name'] = $item['name'];
                $row['rel_number'] = $this->filterPhone($item['number']);
                $row['create_date'] = $current_time;
                $row['u_code'] = $u_code;
                $row['device_id'] = $device_id;
	            $rowValue .= "('{$row['rel_name']},{$row['rel_number']},{$row['create_date']},{$row['u_code']}',{$row['device_id']}'),";
                /*self::$db->replaceInto('my_user_data_relation')
                    ->values($row)->exec();*/
            }
	        $connect->exec($sql.trim($rowValue,','));
        }
    }
}