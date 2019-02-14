<?php
namespace App\AdminBundle\Common;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CommonFunction{
	protected $pool = [];
	protected $classMaps = [];

	public static function getContext($url,$post_data=null,$method='post',$headers=NULL,$userAgent=NULL){
		$ch = curl_init();
		$timeout = 30;
		$userAgent = $userAgent ? $userAgent : '';
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt($ch,  CURLOPT_ENCODING,  "gzip" );
		curl_setopt($ch, CURLOPT_FAILONERROR, false);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		if(!empty($headers)){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_HEADER, true);
		}
		if(!empty($userAgent)) {
			curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		}
		if('post'==strtoupper($method)){
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		if(1 == strpos('$'.$url, "https://")){
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
		$file_contents = curl_exec($ch);
		curl_close($ch);
		return $file_contents;
	}

	public static function strUniqueSort($params=NULL,$cutFix=','){
		if(empty($params)){
			return false;
		}

		if(is_string($params)){
			$params = array_unique(explode($cutFix,trim($params,',')));
		}

		asort($params);
		return implode(',',$params);
	}

	//输出统一格式
	public static function parse_data($data,$code='2000'){
		if(is_string($data)){
			$msg = $data;
			$data = [];
		}else if(is_array($data)){
			$msg = $data['msg'] ? $data['msg'] : '操作成功'; unset($data['msg']);
			$msg = is_array($msg) ? '操作成功' : $msg;
		}else if(null == $data && empty($data)){
			$data = [];
			$msg = '操作成功';
		}else{
			$data = [];
			$msg = '参数错误';
		}
		return ['retCode'=>$code,'result'=>$data,'retInfo'=>$msg,'runTime'=>time()];
	}

	/**
	 * ESB服务
	 * @param array $config
	 * @param string $targetSys
	 * @param string $interface
	 * @param array $params
	 * @param int $callback_type
	 * @param string $msg_id
	 * @return \Exception|mixed
	 */
	public static function getEsbInterface(array $config,string $targetSys, string $interface, array $params = [], int $callback_type = 0, string $msg_id = '')
	{
		try {
			$s = new \SoapClient($config['wsdl'] . '?' . time());
			$soap_var = new \SoapVar(array('targetSys'=>$targetSys,'authName' => $config['targetSys'][$targetSys]['authName'], 'authPwd' => $config['targetSys'][$targetSys]['authPwd'], 'authorization' => md5(date('Y-m-d'))), SOAP_ENC_OBJECT, 'auth', 'ys');
			$u = new \SoapHeader('ys', 'auth', $soap_var, true);
			$s->__setSoapHeaders($u);
			return json_decode($s->destInterface('shop', $targetSys, $interface, json_encode($params), $callback_type, $msg_id), true);
		} catch (\Exception $e) {
			return $e;
		}
	}

	/**
	 * 获取ESB异步响应结果
	 * @param string $msg_id
	 * @return array
	 */
	public static function getResponeResult(array $config,string $targetSys,string $msg_id){
		try {
			$s = new \SoapClient($config['wsdl'] . '?' . time());
			$soap_var = new \SoapVar(array('targetSys'=>$targetSys,'authName' => $config['targetSys'][$targetSys]['authName'], 'authPwd' => $config['targetSys'][$targetSys]['authPwd'], 'authorization' => md5(date('Y-m-d'))), SOAP_ENC_OBJECT, 'auth', 'ys');
			$u = new \SoapHeader('ys', 'auth', $soap_var, true);
			$s->__setSoapHeaders($u);
			return json_decode($s->getResponeResult('admin',$msg_id), true);
		} catch (\Exception $e) {
			return $e;
		}
	}

	/**
	 * UUID
	 */
	public static function uuid()
	{
		$charid = md5(uniqid(mt_rand(), true));
		$uuid = substr($charid, 0, 8)
			. substr($charid, 8, 4)
			. substr($charid, 12, 4)
			. substr($charid, 16, 4)
			. substr($charid, 20, 12);
		return $uuid;
	}

	/**
	 * 手动写入日志
	 * @param string $data 写入内容
	 * @param stirng $dir 写入目录
	 * @param string $method 写入方式
	 * @return ;
	 */
	public static function wlog($data = '', $dir = '', $method = "a+")
	{
		$fileDir = APP_PATH . '/var/logs/' . $dir . date('Y/m/d') . '.log';
		if (!file_exists(dirname($fileDir))) {
			if (!@mkdir(dirname($fileDir), 0777, true)) {
				die(dirname($fileDir) . '创建目录失败!');
			}
		}

		if (is_file($fileDir) && floor(1024 * 1000 * 50) <= filesize($fileDir)) {
			rename($fileDir, dirname($fileDir) . '/' . time() . '-' . basename($fileDir));
		}

		$ip = self::getClientIp();
		$fp = @fopen($fileDir, $method);
		if (!$fp) {
			return 0;
		}
		flock($fp, LOCK_EX);
		$http = (strtolower(@$_SERVER['HTTPS']) == 'on') ? 'https://' : 'http://';
		$http = $http . trim($_SERVER['HTTP_HOST']);
		$submit_type = !empty($_POST) ? 'POST' : 'GET';
		$opt = @fwrite($fp, '[' . date('Y-m-d H:i:s') . ']' . "\r\n" . ' URL来源:' . $_SERVER['HTTP_REFERER'] . "\r\n 请求地址：" . $http . $_SERVER["REQUEST_URI"] . "\r\n 请求Header数据: " . var_export($_SERVER, true) . "\r\n 请求数据(" . $submit_type . "方式): " . var_export($_REQUEST, true) . "\r\n 浏览器 : " . $_SERVER['HTTP_USER_AGENT'] . "\r\n 数据：" . var_export($data, true) . ' 访问IP：' . $ip . "\r\n\r\n");
		fclose($fp);
		return $opt;
	}

	/**
	 * 获取客户端IP地址
	 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
	 * @return string
	 */

	public static function getClientIp($type = 0)
	{
		$type = $type ? 1 : 0;
		static $ip = NULL;
		if ($ip !== NULL) return $ip[$type];
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos = array_search('unknown', $arr);
			if (false !== $pos) unset($arr[$pos]);
			$ip = trim($arr[0]);
		} elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		// IP地址合法验证
		$long = sprintf("%u", ip2long($ip));
		$ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
		return $ip[$type];
	}

	/**
	 * 根据IP地址获取地理位置
	 * @param $ip
	 * @return bool|mixed
	 */
	public static function getAddByIp($ip=''){
		if(empty($ip)){
			$ip = self::getClientIp();
		}
		$appcode = '1beecc7ffa4047499bd1f475e589b0d5';
		//$url = 'http://freeapi.market.alicloudapi.com/ip';//详细版
		$url = 'http://api01.aliyun.venuscn.com/ip';//普通版
		$json = self::getContext($url.'?ip='.$ip,'','get',["Authorization:APPCODE ".$appcode,'content-type: application/json']);
		preg_match('/((?<={")[\s\S]*?(?<=}))$/is', $json,$json);
		return json_decode('{"'.$json[0],true);
	}

	//导出表格
	public static function output_excel($titArr, $data, $fileName, $type = 1,$suffix='.xls') {
		$labelStr = $outStr = '';
		if ($titArr) {
			foreach ($titArr as $val) {
				$labelStr .= '<td>' . $val . '</td>';
			}
			$labelStr = '<tr style="vnd.ms-excel.numberformat:@">' . $labelStr . '</tr>';
		}
		if ($data) {
			$showArr = array_keys($titArr);
			foreach ($data as $val) {
				$rowStr = '';
				foreach ($showArr as $name) {
					$rowStr .= '<td>' . $val[$name] . '</td>';
				}
				$outStr .= '<tr style="vnd.ms-excel.numberformat:@">' . $rowStr . '</tr>';
			}
		}
		$outStr = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><meta http-equiv=Content-Type content="text/html; charset=utf-8"><meta name=ProgId content=Excel.Sheet></head><body><table border="1">' . $labelStr . $outStr . '</table></body></html>';
		//网页导出
		if (1 == $type) {
			header("Content-Type:application/vnd.ms-excel");
			header("Content-Disposition:attachment;filename={$fileName}{$suffix}");
			header("Pragma:no-cache");
			header("Expires:0");
			echo $outStr;
		}
	}

	/**
	 * 下划线转驼峰
	 * step1.原字符串转小写,原字符串中的分隔符用空格替换,在字符串开头加上分隔符
	 * step2.将字符串中每个单词的首字母转换为大写,再去空格,去字符串首部附加的分隔符.
	 */
	public static function camelize($uncamelized_words,$separator='_')
	{
		$uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
		return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
	}

	/**
	 * 驼峰命名转下划线命名
	 * 小写和大写紧挨一起的地方,加上分隔符,然后全部转小写
	 */
	public static function uncamelize($camelCaps,$separator='_'){
		return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
	}

	/**
	 * 二维数组去重
	 * @param $arr   待去重数组
	 * @param $k_arr 需要排重的键名，默认数组所有键
	 * @return []
	 */
	public static function  array_unique_2d($arr,$k_arr=NULL){
		$arr_out =array();
		$i=0;
		foreach($arr as $k => $v) {
			$k_arr_ = empty($k_arr) ? $v : $k_arr;
			$val = '';
			foreach($k_arr_ as $value){
				$val .= $value;
			}
			$key_out = md5($val);
			if(array_key_exists($key_out,$arr_out)){
				continue;
			}
			else{
				$arr_out[$key_out] = $arr[$k];
				$arr_wish[$i] = $arr[$k];
			}
			$i++;
		}
		return $arr_wish;
	}

	/**
	 * 根据给出时间往后推指定个月份
	 * @params $format 格式化日期
	 */
	public static function echoDateList($date,$nums=1,$format='Y-m'){
		$date_list[] = date($format,strtotime($date));
		for($i=1;$i<$nums;$i++){
			$date_list[] = $date = self::getNextMonth($date,$format);
		}
		krsort($date_list);
		$date_list = explode(',',implode(',',$date_list));
		return $date_list;
	}

	/**
	 * 根据给出时间往后推指定个日期
	 * @params $format 格式化日期
	 */
	public static function getDaysList($start,$days=1){
		$dt_start = strtotime($start);
		$dt_end = strtotime("$start -$days day");
		$list = [];
		while ($dt_start>$dt_end){
			$list[] = date('Y-m-d',$dt_start);
			$dt_start = strtotime('-1 day',$dt_start);
		}
		krsort($list);
		$list = explode(',',implode(',',$list));
		return $list;
	}

	/**
	 * 根据日期获取当月周列表
	 * @param $date
	 */
	public static function getMonthWeekBydateList($date){
		$ret=array();
		$stimestamp=strtotime($date);
		$mdays=date('t',$stimestamp);
		$msdate=date('Y-m-d',$stimestamp);
		$medate=date('Y-m-'.$mdays,$stimestamp);
		$etimestamp = strtotime($medate);
		//获取第一周
		$zcsy=6-date('w',$stimestamp);//第一周去掉第一天的剩余天
		$zcs1=$msdate;
		$zce1=date('Y-m-d',strtotime("+$zcsy day",$stimestamp));
		$ret[1]=['zhout'=>"第1周",'date'=>$zcs1.'~'.$zce1];
		//获取中间周次
		$jzc=0;

		$jzc0="";
		$jzc6="";
		for($i=$stimestamp; $i<=$etimestamp; $i+=86400){
			if(date('w', $i) == 0){$jzc0++;}
			if(date('w', $i) == 6){$jzc6++;}
		}
		if($jzc0==5 && $jzc6==5)
		{
			$jzc=5;
		}else{
			$jzc=4;
		}
		date_default_timezone_set('PRC');
		$t = strtotime('+1 monday '.$msdate);
		$n = 1;
		for($n=1; $n<$jzc; $n++) {
			$b = strtotime("+$n week -1 week", $t);
			$dsdate=date("Y-m-d", strtotime("-1 day", $b));
			$dedate=date("Y-m-d", strtotime("5 day", $b));
			$jzcz=$n+1;
			$ret[$jzcz]=['zhout'=>"第".$jzcz."周",'date'=>$dsdate.'~'.$dedate];
		}
		//获取最后一周
		$zcsy=date('w',$etimestamp);//最后一周是周六日
		$zcs1=date('Y-m-d',strtotime("-$zcsy day",$etimestamp));
		$zce1=$medate;
		$jzcz=$jzc+1;
		$ret[$jzcz]=['zhout'=>"第".$jzcz."周",'date'=>$zcs1.'~'.$zce1];
		return $ret;
	}

	/**
	 * 根据日期往前推算指定数量的周列表
	 * @params int $nums 往前推几周【1代表当前周】
	 */
	public static function getBeforeZhoutListByDate($date,$nums=1,$type=false){
		$ret = [];
		$t = $date;
		for($n=0; $n<$nums; $n++) {
			$weekStartEnd = self::weekStartEnd($t);
			if($type){
				$ret[] = ['zhout'=>date('W',strtotime($weekStartEnd[start])),'date'=>$weekStartEnd[start]];
			}else{
				$ret[] = date('W',strtotime($weekStartEnd[start]));
			}
			$t = date('Y-m-d',strtotime("$weekStartEnd[start] - 7 days"));
		}
		return $ret;
	}

	/**
	 * 获取指定周的起始和结束时间
	 */
	public static function weekStartEnd($date) {
		$d = $date.' 00:00:00';
		$s = strtotime($d);
		$w = date('w', strtotime($d)); // 得到指定日期是星期几
		$add1 = 0 - $w;  // 周日，和指定日期相差的天数
		$add2 = 6 - $w;  // 周六，和指定日期相差的天数
		$s1 = strtotime("$add1 days", $s);
		$s2 = strtotime("$add2 days", $s);
		return array('start' => date("Y-m-d", $s1),'end' => date("Y-m-d", $s2));
	}

	/**
	 * 获取下一个月日期
	 * @param $date
	 * @param $format
	 * @return false|string
	 */
	public static function getNextMonth($date,$format){
		$timestamp=strtotime($date);
		$nextMonth = date($format,strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
		//$lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
		return $nextMonth;
	}

	/*
	*function：计算两个日期相隔多少年，多少月，多少天
	*param string $date1[格式如：2011-11-5]
	*param string $date2[格式如：2012-12-01]
	*return array array('年','月','日');
	*/
	public static function diffDate($date1,$date2){
		if(strtotime($date1)>strtotime($date2)){
			$tmp=$date2;
			$date2=$date1;
			$date1=$tmp;
		}
		list($Y1,$m1,$d1)=explode('-',$date1);
		list($Y2,$m2,$d2)=explode('-',$date2);
		$Y=$Y2-$Y1;
		$m=$m2-$m1;
		$d=$d2-$d1;
		if($d<0){
			$d+=(int)date('t',strtotime("-1 month $date2"));
			$m--;
		}
		if($m<0){
			$m+=12;
			$Y--;
		}
		return array('year'=>$Y,'month'=>$m,'day'=>$d);
	}

	/**
	 * 取两个日期间的天数
	 * @param $day1
	 * @param $day2
	 * @return int
	 */
	public static function diffBetweenTwoDays ($day1, $day2){
		$second1 = strtotime($day1);
		$second2 = strtotime($day2);

		if ($second1 < $second2) {
			$tmp = $second2;
			$second2 = $second1;
			$second1 = $tmp;
		}
		return intval(abs($second1 - $second2) / 86400);
	}

	/**
	 * 人民币小写转大写
	 * @param string $number 数值
	 * @param string $int_unit 币种单位，默认"元"，有的需求可能为"圆"
	 * @param bool $is_round 是否对小数进行四舍五入
	 * @param bool $is_extra_zero 是否对整数部分以0结尾，小数存在的数字附加0,比如1960.30，
	 *             有的系统要求输出"壹仟玖佰陆拾元零叁角"，实际上"壹仟玖佰陆拾元叁角"也是对的
	 * @return string
	 */
	public static function numToRmb($number = 0, $int_unit = '元', $is_round = true, $is_extra_zero = false){
		// 将数字切分成两段
		$parts = explode('.', $number, 2);
		$int = isset($parts[0]) ? strval($parts[0]) : '0';
		$dec = isset($parts[1]) ? strval($parts[1]) : '';

		// 如果小数点后多于2位，不四舍五入就直接截，否则就处理
		$dec_len = strlen($dec);
		if (isset($parts[1]) && $dec_len > 2)
		{
			$dec = $is_round
				? substr(strrchr(strval(round(floatval("0.".$dec), 2)), '.'), 1)
				: substr($parts[1], 0, 2);
		}

		// 当number为0.001时，小数点后的金额为0元
		if(empty($int) && empty($dec))
		{
			return '零';
		}

		// 定义
		$chs = array('0','壹','贰','叁','肆','伍','陆','柒','捌','玖');
		$uni = array('','拾','佰','仟');
		$dec_uni = array('角', '分');
		$exp = array('', '万');
		$res = '';

		// 整数部分从右向左找
		for($i = strlen($int) - 1, $k = 0; $i >= 0; $k++)
		{
			$str = '';
			// 按照中文读写习惯，每4个字为一段进行转化，i一直在减
			for($j = 0; $j < 4 && $i >= 0; $j++, $i--)
			{
				$u = $int{$i} > 0 ? $uni[$j] : ''; // 非0的数字后面添加单位
				$str = $chs[$int{$i}] . $u . $str;
			}
			//echo $str."|".($k - 2)."<br>";
			$str = rtrim($str, '0');// 去掉末尾的0
			$str = preg_replace("/0+/", "零", $str); // 替换多个连续的0
			if(!isset($exp[$k]))
			{
				$exp[$k] = $exp[$k - 2] . '亿'; // 构建单位
			}
			$u2 = $str != '' ? $exp[$k] : '';
			$res = $str . $u2 . $res;
		}

		// 如果小数部分处理完之后是00，需要处理下
		$dec = rtrim($dec, '0');

		// 小数部分从左向右找
		if(!empty($dec))
		{
			$res .= $int_unit;

			// 是否要在整数部分以0结尾的数字后附加0，有的系统有这要求
			if ($is_extra_zero)
			{
				if (substr($int, -1) === '0')
				{
					$res.= '零';
				}
			}

			for($i = 0, $cnt = strlen($dec); $i < $cnt; $i++)
			{
				$u = $dec{$i} > 0 ? $dec_uni[$i] : ''; // 非0的数字后面添加单位
				$res .= $chs[$dec{$i}] . $u;
			}
			$res = rtrim($res, '0');// 去掉末尾的0
			$res = preg_replace("/0+/", "零", $res); // 替换多个连续的0
		}
		else
		{
			$res .= $int_unit . '整';
		}
		return $res;
	}

	/**
	 * 通过身份证获取生日
	 * @param $idcard
	 * @return bool|false|string
	 */
	public static function getBirthDayByIdCard($idcard){
		if(!self::isIdCard($idcard)) return '';
		$bir = substr($idcard, 6, 8);
		$year = (int) substr($bir, 0, 4);
		$month = (int) substr($bir, 4, 2);
		$day = (int) substr($bir, 6, 2);
		return str_pad($year,2,"0",STR_PAD_LEFT) . "-" . str_pad($month,2,"0",STR_PAD_LEFT) . "-" . str_pad($day,2,"0",STR_PAD_LEFT);
	}

	/**
	 * 通过身份证获取性别
	 * @param $idcard
	 * @return bool|false|string
	 */
	public static function getSexByIdCard($idcard){
		if(!self::isIdCard($idcard)) return '';
		$sexint = (int)substr($idcard,16,1);
		$sex = $sexint % 2 === 0 ? '女' : '男';
		return $sex;
	}

	/**
	 * 通过身份证获取年龄
	 * @param $idcard
	 * @return bool|false|string
	 */
	public static function getAgeByIdCard($idcard){
		if(!self::isIdCard($idcard)) return '';
		$sub_str = substr($idcard,6,4);
		$now = date("Y",time());
		return $now-$sub_str;
	}
	/**
	 * 判断是不是合法的身份证号
	 * @param $number
	 * @return bool
	 */
	public static function isIdCard(&$number) {
		$number = trim($number);
		$reg = '/(^[1-9]\d{5}(18|19|([23]\d))\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$)|(^[1-9]\d{5}\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}$)/';
		if(!preg_match($reg, $number)){
			return false;
		}
		//检查是否是身份证号
		// 转化为大写，如出现x
		$number = strtoupper($number);
		//加权因子
		$wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
		//校验码串
		$ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
		//按顺序循环处理前17位
		$sigma = 0;
		for($i = 0;$i < 17;$i++){
			//提取前17位的其中一位，并将变量类型转为实数
			$b = (int) $number{$i};      //提取相应的加权因子
			$w = $wi[$i];     //把从身份证号码中提取的一位数字和加权因子相乘，并累加
			$sigma += $b * $w;
		}
		//计算序号
		$snumber = $sigma % 11;
		//按照序号从校验码串中提取相应的字符。
		$check_number = $ai[$snumber];
		if($number{17} == $check_number){
			return true;
		}else{
			return false;
		}
	}

	//计算两个时间差距
	public static function get_time_diff($the_time)
	{
		$now_time = time();
		$show_time = strtotime($the_time);

		$dur = $now_time - $show_time;

		if($dur < 60){
			return $dur.'秒前';
		}else if($dur < 3600){
			return floor($dur/60).'分钟前';
		}else if($dur < 86400) {
			return floor($dur/3600).'小时前';
		}else if($dur < 259200) {//3天内
			return floor($dur / 86400) . '天前';
		}else{
			return $the_time;
		}
	}
}