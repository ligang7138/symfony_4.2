<?php
/**
 * Created by PhpStorm.
 * User: yxl
 * Date: 18-10-30
 * Time: 上午11:35
 */

namespace App\AdminBundle\Exception;



use Symfony\Component\HttpFoundation\Response;

class MessageException extends \Exception
{
    protected $script ;
    protected $timeout ;
    public function __construct(string $message = '', string $script='close_tab();',$timeout=0,$code = Response::HTTP_PRECONDITION_FAILED)
    {
        $this->script = $script;
        $this->timeout = $timeout;
        $message = "<script>alert('{$message}');setTimeout(function(){ {$script} },{$timeout})</script>";
        parent::__construct($message,$code);
    }
}