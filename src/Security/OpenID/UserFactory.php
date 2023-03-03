<?php

namespace App\Security\OpenID;

use App\OpenIdOidcTokenTrait;
use App\Security\User;
use Drenso\OidcBundle\Security\Authentication\Token\OidcToken;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserFactory
{
    
    use OpenIdOidcTokenTrait;
    private $JWTTokenManager;

    public function __construct(JWTTokenManagerInterface $JWTTokenManager)
    {
        $this->JWTTokenManager = $JWTTokenManager;
    }

    public function createUser(OidcToken $token): UserInterface
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $user->setUsername($this->getUsernameFromSub($token)); 
        $user->setPassword('notused');
        $user->setEmail($token->getEmail());

        $this->saveUser($user);
        
        return $user;
    }

    private function saveUser(User $user)
    {
        $data = "$".$user->getUserIdentifier()." = [
    'username' => '".$user->getUserIdentifier()."',
    'pwd' => '".$user->getPassword()."',
    'email' => '".$user->getEmail()."',
    'roles' => [
        '".implode("', '", $user->getRoles())."'
    ]
];
";
        file_put_contents(__DIR__.'/../../../config/users.php', $data, FILE_APPEND);
    }
}
