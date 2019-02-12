<?php
namespace App\EventListener;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\HttpUtils;


class LoginListener extends DefaultAuthenticationSuccessHandler {

    protected $service;

    public function __construct(HttpUtils $httpUtils, array $options,$service)
    {
        parent::__construct( $httpUtils, $options );
        $this->service = $service;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
	    $user = $token->getUser();
	    $em = $this->service->get('doctrine')->getManager();
        $user->setALoginTime(new \DateTime(date('Y-m-d H:i:s')));
        $em->persist($user);
        $em->flush();
        $token->setUser($user);
        return $this->httpUtils->createRedirectResponse($request, $this->service->get('router')->generate('admin_admin_index'));
    }
}

