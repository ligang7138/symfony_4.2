<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-6-20
 * Time: 上午11:19
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Pheanstalk\Pheanstalk;

/**
 * @Route("/beanstalk")
 * Class PheanstalkController
 * @package App\Controller
 */
class PheanstalkController extends Controller
{
	/**
	 * @Route("/case")
	 */
	public function indexAction(){
		$pheanstalk = new Pheanstalk('127.0.0.1',11300);

//		$isCon = $pheanstalk->getConnection()->isServiceListening();

// ----------------------------------------
// producer (queues jobs)

		$pheanstalk
			->useTube('testtube')
			->put("job payload goes here\n");
		echo '<pre />';
//		print_r($pheanstalk->listTubesWatched());die;
		print_r($pheanstalk->stats());
		print_r($pheanstalk->statsTube('default'));die;
		print_r($pheanstalk->listTubes());die;

		print_r($pheanstalk);die;
// ----------------------------------------
// worker (performs jobs)

		$job = $pheanstalk
			->watch('testtube')
			->ignore('default')
			->reserve();

		echo $job->getData();

		$pheanstalk->delete($job);

// ----------------------------------------
// check server availability

		$pheanstalk->getConnection()->isServiceListening(); // true or false
		die;
	}
}