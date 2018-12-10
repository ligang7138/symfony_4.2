<?php
namespace App\Yszc\AdminBundle\Controller;

use App\Yszc\AdminBundle\Common\CommonFunction;
use App\Yszc\AdminBundle\Entity\YsLogs;
use App\Yszc\AdminBundle\Entity\YsOrder;
use App\Yszc\AdminBundle\Entity\YsTransaction;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/sync")
 */
class CronController extends CommonController{
	/**
	 * 同步支付结果
	 * @Route("/order_result")
	 */
    public function getHandleOrderAndPayInfo(){
        $service = $this->createService('Order.OrderService');
        $orderInfo = $service->getHandleOrderInfo(13);
        $result = [];
        $ret = json_encode(['msg'=>'没有要处理的数据！','code'=>500]);
        foreach ($orderInfo as $key=>$val){
            $order_no = $val['order_serial_num'];
            $trade_type = 2;//支付订单
            $res = self::webService('pay','order_find',['up_trxid'=>$order_no,'trade_type'=>$trade_type],2);
            $em = $this->getDoctrine()->getManager();
            $order = $this->getDoctrine()->getRepository('AdminBundle:YsOrder')->find($val['order_id']);
            $up_status = $order->getOrderStatus();
            $curr_status = 13;//订单状态
            $acc_status = 6;//交易记录状态
            if(2000==$res['code']){
                $curr_status = 3;
                $acc_status = 2;
            }else if(5000==$res['code']){
                $curr_status = 2;
                $acc_status = 7;
            }
            $msg = $res['msg'];
            $order->setOrderStatus($curr_status);
            $order->setPayResultMsg($msg);
            $em->persist($order);

            // 记录支付成功log
            $logs = new YsLogs();
            $logs->setCurrStatus($curr_status);//操作后状态
            $logs->setUpStatus($up_status);// 上一步状态
            $logs->setLogAddTime(new \DateTime(date('Y-m-d H:i:s')));
            $logs->setLogAdminName('system');
            $logs->setOrderId($val['order_id']);
            $logs->setLogRemark($msg);
            $em->persist($logs);

            //交易流水
	        $transaction = $this->getDoctrine()->getRepository('AdminBundle:YsTransaction')->findOneBy(['orderId'=>$val['order_id'],'tStatus'=>6]);
            $transaction->setTStatus($acc_status);
            $em->persist($transaction);
            $em->flush();

	        if($curr_status == 3){
		        //发送全款--支付成功
		        $user_info = $this->getDoctrine()->getConnection('fenqi')->fetchAssoc("select * from my_users where u_code='{$order->getUCode()}'");
		        $content = '尊敬的商户，用户'. $user_info['u_name'] .'已支付成功一笔全款订单(订单号:'. $order->getOrderNo() .')，合计支付'. $order->getOrderFatPayAmount() .'元，请及时安排发货！';
		        $this->addMessage($order->getPartnerId(),'全款订单--已支付',$content,2);

		        $params = ['msg_type'=>1,'order_id'=>$val['order_id'],'params'=>json_encode(['total_menoy'=>$order->getOrderFatPayAmount()])];
		        $this->parseData(self::webService('api', 'order_msg', $params));

	        }
	        $result[] = $order_no;
        }
        if($result){
	        $ret = json_encode(['msg'=>'ok！']);
        }
        die($ret);
    }

	/**
	 * 同步已完成结果
	 * @Route("/order_end")
	 */
	public function syncOrderEnd(){
		$orderList = $this->getDoctrine()->getManager()->createQueryBuilder()->select('o')
			->from('AdminBundle:YsOrder', 'o')
			->Where('o.orderStatus in(5,8)')->andWhere('o.orderPayType=2')
			->getQuery()->getResult();
		$result = [];
		$ret = json_encode(['msg'=>'没有要处理的数据！','code'=>500]);
		foreach ($orderList as $order){
			$diff_days = self::diff2Date($order->getOrderAddTime()->format('Y-m-d H:i:s'));
			if(5==$order->getOrderStatus() && $diff_days<7){
				continue;
			}
			$order_id = $order->getOrderId();
			$em = $this->getDoctrine()->getManager();
			$up_status = $order->getOrderStatus();
			$order->setOrderStatus(10);
			$em->persist($order);

			// 记录log
			$logs = new YsLogs();
			$logs->setCurrStatus(10);//操作后状态
			$logs->setUpStatus($up_status);// 上一步状态
			$logs->setLogAddTime(new \DateTime(date('Y-m-d H:i:s')));
			$logs->setLogAdminName('system');
			$logs->setOrderId($order_id);
			$logs->setLogRemark('订单已完成');
			$em->persist($logs);
			$em->flush();

            $params = ['msg_type'=>2,'order_id'=>$order_id];
            $this->parseData(self::webService('api', 'order_msg', $params));

			//$this->addUserMessage($order->getUCode(),'订单',$order->getOrderNo().'支付成功',2);
			$result[] = $order_id;
		}
		if($result){
			$ret = json_encode(['msg'=>'ok！']);
		}
		die($ret);
	}

	/**
	 * @param $day1   结束日期
	 * @return int
	 */
	private static function diff2Date($day1){
		$second1 = time();
		$second2 = strtotime($day1);
		return intval(($second1 - $second2) / 86400);
	}

}
