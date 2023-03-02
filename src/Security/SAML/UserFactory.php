<?php

namespace App\Security\SAML;

use App\Security\User;
use Hslavich\OneloginSamlBundle\Security\User\SamlUserFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserFactory implements SamlUserFactoryInterface
{
    public function createUser($username, array $attributes = []): UserInterface
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $user->setUsername($username);
        $user->setPassword('notused');
        $user->setEmail($attributes['mail'][0]);
        
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
