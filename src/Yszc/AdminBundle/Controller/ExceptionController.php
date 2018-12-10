<?php
/**
 * Created by GroupTour.
 * User: ydmx_lei
 * Time: 2016/12/9 15:18
 */

namespace App\Yszc\AdminBundle\Controller;
use App\Yszc\AdminBundle\Controller\CommonController;
use Symfony\Component\Debug\Exception\FlattenException;

//异常类
class ExceptionController extends CommonController
{
    public function showExceptionAction(FlattenException $exception){
        var_dump($exception->getMessage());die;
       return $this->show('index/error');
    }
}