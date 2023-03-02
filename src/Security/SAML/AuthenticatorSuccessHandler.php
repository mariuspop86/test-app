<?php

namespace App\Security\SAML;

use App\Security\User;
use Hslavich\OneloginSamlBundle\Security\Http\Authentication\SamlAuthenticationSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\HttpUtils;

class AuthenticatorSuccessHandler extends SamlAuthenticationSuccessHandler
{
    private $JWTManager;

    public function __construct(
        JWTTokenManagerInterface $JWTManager,
        HttpUtils $httpUtils, 
        array $options = [], 
        LoggerInterface $logger = null 
    )
    {
        parent::__construct($httpUtils, $options, $logger);
        $this->JWTManager = $JWTManager;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        try {
            /** @var User $user */
            $user = $token->getUser();
            $authToken = $this->JWTManager->create($user);
             
            return new RedirectResponse(
                $this->determineTargetUrl($request). '?bearer='.$authToken
            );
        }catch (\Throwable $exception){
            $this->logger->error('Failed to login user', [$exception->getMessage()]);
            return new RedirectResponse('/saml/login');
        }
    }
}
