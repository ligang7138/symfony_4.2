<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-6-14
 * Time: 下午5:03
 */

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Hprose\Http\Server;
class IndexController
{
	public function index(){

//		echo 2343;die;

		$number = random_int(0, 100);

		return new Response(
			'<html><body>Lucky number: '.$number.'</body></html>'
		);
	}
}