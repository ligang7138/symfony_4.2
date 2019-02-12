<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-3-21
 * Time: 上午9:31
 */

namespace Bundles\PayBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
	public function getUser($bid){
		return $this->findOneBy(['uId' => $bid]);
	}
}