<?php
/**
 * Created by GroupTour.
 * User: AdminBundle
 * Time: 2016/12/22 11:52
 */

namespace App\AdminBundle\Security;

use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class AdminPasswordEncoder extends BasePasswordEncoder
{

    private $cost;

    public function __construct( $cost)
    {
        $cost = intval( $cost);
        if( $cost < 4 || $cost > 31 )
        {
            throw new \InvalidArgumentException('Cost too long , it must be in the range of 4-31');
        }
        $this->cost = sprintf('%02d' , $cost);
    }

    public function encodePassword( $raw , $salt = null )
    {
        if( $this->isPasswordTooLong($raw) )
        {
            throw new BadCredentialsException('密码错误！');
        }
        return password_hash($raw, PASSWORD_DEFAULT, array('cost' => $this->cost));
        //return md5( md5( $raw ) . $salt );
    }

    public function isPasswordValid($encoded, $raw, $salt = null)
    {
        if ($this->isPasswordTooLong($raw))
        {
            return false;
        }
         return password_verify($raw,$encoded);
        //return md5( md5( $raw).$salt) === $encoded;
    }

}