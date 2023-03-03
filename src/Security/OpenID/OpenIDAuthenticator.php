<?php

namespace App\Security\OpenID;

use Drenso\OidcBundle\Exception\OidcException;
use Drenso\OidcBundle\OidcClient;
use Drenso\OidcBundle\Security\Authentication\Token\OidcToken;
use Drenso\OidcBundle\Security\Exception\OidcAuthenticationException;
use Drenso\OidcBundle\Security\UserProvider\OidcUserProviderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class OpenIDAuthenticator extends AbstractAuthenticator
{
    private $OidcClient;
    private $oidcUserProvider;
    private $JWTTokenManager;

    public function __construct(
        OidcClient $OidcClient, 
        OidcUserProviderInterface $oidcUserProvider, 
        JWTTokenManagerInterface $JWTTokenManager
    ) {
        $this->OidcClient = $OidcClient;
        $this->oidcUserProvider = $oidcUserProvider;
        $this->JWTTokenManager = $JWTTokenManager;
    }

    public function supports(Request $request): ?bool
    {
        return $request->query->has('code');
    }

    public function authenticate(Request $request): ?SelfValidatingPassport
    {
        try {
            // Try to authenticate the request
            if (($authData = $this->OidcClient->authenticate($request)) === NULL) {
                return NULL;
            }
            
            // Retrieve the user date with the authentication data
            $userData = $this->OidcClient->retrieveUserInfo($authData);

            // Create token
            $token = (new OidcToken())
                ->setUserData($userData)
                ->setAuthData($authData);
          
            $user = $this->oidcUserProvider->loadUserByToken($token);
            
            return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));

        } catch (OidcException $e) {
            throw new OidcAuthenticationException("Request validation failed", NULL, $e);
        }
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        try {
            /** @var UserInterface $user */
            $user = $token->getUser();

            $authToken = $this->JWTTokenManager->create($user);

            return new RedirectResponse(
                '/api/v1/user/?bearer='.$authToken
            );
        } catch (\Exception $exception) {
            return new RedirectResponse('/openid/login');
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
