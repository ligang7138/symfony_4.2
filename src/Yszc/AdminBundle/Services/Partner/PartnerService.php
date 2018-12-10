<?php
namespace App\Yszc\AdminBundle\Services\Partner;
/**
 * 商户服务
 */
interface  PartnerService
{
    //获取商户列表
    public function findList();
    public function getDaturms($partner_id,$pd_type);
}