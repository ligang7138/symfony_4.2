<?php
namespace App\Yszc\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Yszc\AdminBundle\Common\CommonFunction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use App\Yszc\AdminBundle\Controller\CommonController;

/**
 * @Route("/upload")
 */
class UploadController extends CommonController{

    /**
     * 图片相册
     * @Route("/admin/upload/open_photo.html")
     */
    public function openPhoto(){
	  return $this->show('common/open_photo',[]);
    }

    /**
     * 商户上传资料
     * @param Request $request
     * @Route("/upload.html")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function partnerUpload(Request $request){
        $p_id = $request->get('p_id',0);
        $pdType = $request->get('f_type');
        $temp = 'partner/upload';
        if('post'==strtolower($request->getMethod())){
            $temp = 'partner/upload2';
        }
        return $this->show($temp,['p_id' => $p_id,'f_type'=>$pdType]);
    }

    /**
     * 获取商户资料信息
     * @param Request $request
     * @Route("/getDaturmInfo.html")
     * @Method("POST")
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function getDaturmInfo(Request $request){
        $p_id = $request->get('p_id',0);
        $p_type = $request->get('f_type','');
        if(!empty($p_id) && !empty($p_type)){

            /** @var PartnerServiceImpl $partnerService */
            $partnerService = $this->createService('Partner.PartnerService');
            $daturm_list = $partnerService->getDaturmInfo($p_id,$p_type);
            if(empty($daturm_list)){
                $return = ['code'=>500,'msg'=>'没有数据'];
            }else{
                $config = $this->getParameter('admin_bundle');
                $return = ['code'=>200,'daturm_list'=>$daturm_list,'fileHost'=>$config['fileHost'],'file_type'=>$config['file_type'][$p_type]];
            }
        }else{
            $return = ['code'=>500,'msg'=>'没有数据'];
        }

        return  $this->parseData($return);
    }
    /**
     * 删除图片
     * @Route("/del.html")
     * @Method("POST")
     */
    public function delPhoto(Request $request){
        $id = $request->get('id','0');
        $pdType = $request->get('p_type','');
        try{
            if(!empty($id) && !empty($pdType)){
                $em = $this->getDoctrine()->getManager();
                $userDaturm = $em->getRepository('AdminBundle:YsPartnerDaturm')->findOneBy(['id'=>$id,'pdType' => $pdType]);
                $em->remove($userDaturm);
                $em->flush();
//                self::webService('channel','del_file',['url'=>$userDaturm->getPdUrl()]);
                return  $this->parseData(['code'=> 200,'msg'=>'删除成功!']);
            }else{
                return  $this->parseData(['code'=> 201,'msg'=>'删除!']);
            }

        }catch (\Exception $e){
            return  $this->parseData(['msg'=>$e->getMessage(),'code'=>500]);
        }
    }

    /**
     * 上传时删除图片-加服务器图片
     * @Route("/del_img.html")
     */
    public function delImg(Request $request){
        $url = $request->get('url');
//        self::webService('channel','del_file',['url'=>$url]);
        return  $this->parseData(['code'=> 200,'msg'=>'删除成功!']);
    }


    /**
     * 资料图片位置调整
     * @Route("/admin/upload/set_photo_position.html")
     */
    public function setPhotoPosition(Request $request){
	  if('post' == strtolower($request->getMethod())){
		$f_id = intval($request->get('f_id'));
		$degree = trim($request->get('degree'));
		$em = $this->getDoctrine()->getManager();
		$user_daturm = $this->getDoctrine()->getRepository('AdminBundle:MyUserDaturm')->findOneBy(['udId'=>$f_id]);
		if(empty($user_daturm)){
		    return  $this->parseData(['msg'=>'该图片已不存在！','closeCurrTab'=>false]);
		}
		$user_daturm->setUdDegree($degree);
		$em->persist($user_daturm);
		$em->flush();
		return  $this->parseData(['msg'=>'保存成功！']);
	  }else{
		return  $this->parseData(['code'=>500,'msg'=>'请求错误！','closeCurrTab'=>false]);
	  }
    }

    /**
     * 图片保存
     * @param type $bId 申请编号
     * @Route("/admin/upload/save.html")
     * @Method("POST")
     */
    public function saveUploadFile(Request $request){
	  $em = $this->getDoctrine()->getManager();
	  $user = $this->getUser();
	  $borrow_service = $this->createService('Borrow.BorrowService');
	  $file_list = $request->get('fileList');
	  $b_id = $request->get('bId');
	  $fType = $request->get('fType');
	  $apply_info = $this->getDoctrine()->getRepository('AdminBundle:MyBorrowApply')->findOneBy(['bId'=>$b_id]);
	  if($apply_info){
		foreach($file_list as $file){
		    $userDaturm = new \AdminBundle\Entity\MyUserDaturm();
		    $userDaturm->setUCode($apply_info->getUCode());
		    $userDaturm->setBId($b_id);
		    $userDaturm->setUdUploadTime(new \DateTime(date('Y-m-d H:i:s')));
		    $userDaturm->setUdUrl($file);
		    $userDaturm->setUdType($fType);
		    $userDaturm->setAId($user->getAid());
		    $em->persist($userDaturm);
		}
		$em->flush();
		$config = $this->getParameter('admin_bundle');

		$daturm_list = $borrow_service->getDaturmInfo($b_id, $fType);;

		return  $this->parseData(['msg'=>'保存成功!','daturm_list'=>$daturm_list,'fileHost'=>$config['fileHost'],'file_type'=>$config['file_type'][$fType]]);
	  }else{
		return  $this->parseData(['msg'=>'进件信息不存在,保存失败!','code'=>500]);
	  }
    }

    /**
     *保存编辑后的图片
     * @Route("/admin/upload/edit_save.html")
     * @Method("POST")
     */
    public function editAndUpload(Request $request)
    {
	    $file_url = $request->get('url');
	    $ud_id = str_replace('image','',$request->get('udid'));
	    try{
	    $em = $this->getDoctrine()->getManager();
	    $userDaturm = $this->getDoctrine()->getRepository('AdminBundle:MyUserDaturm')->findOneBy(['udId'=>$ud_id]);
	    $userDaturm->setUdNewUrl($file_url);
	    $em->persist($userDaturm);
		$em->flush();
		return  $this->parseData(['msg'=>'保存成功!','udid'=>$ud_id]);
		}catch (\Exception $e){
			return  $this->parseData(['msg'=>$e->getMessage(),'code'=>500]);
		}
	}

    /**
     *
     * @param $name  服务名称
     * @return 服务对象
     */

    public function createService($serverName)
    {
        return $this->get('application_service')->getService($serverName);
    }
}
