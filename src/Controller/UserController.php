<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1", name="api_v1")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="get_user", methods="GET")
     */
    public function user(TokenStorageInterface $tokenStorage, JWTTokenManagerInterface $jwtManager): 
    JsonResponse
    {
        try {
            $decodedJwtToken = $jwtManager->decode($tokenStorage->getToken());
        } catch (JWTDecodeFailureException $e) {
            $decodedJwtToken= [];
        }

        return new JsonResponse($decodedJwtToken);
    }
}
