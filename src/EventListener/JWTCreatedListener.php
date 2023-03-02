<?php

namespace App\EventListener;

use App\Security\User;
use App\Security\UserProvider;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener
{
    private $userProvider;
    
    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    /**
     * @param JWTCreatedEvent $event
     *
     * @return void
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $payload = $event->getData();
        /** @var $user User */
        $user = $this->userProvider->loadUserByIdentifier($payload['username']);
        $payload['email'] = $user->getEmail();
        $event->setData($payload);
    }
}
