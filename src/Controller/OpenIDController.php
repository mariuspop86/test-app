<?php

namespace App\Controller;

use Drenso\OidcBundle\Exception\OidcConfigurationException;
use Drenso\OidcBundle\Exception\OidcConfigurationResolveException;
use Drenso\OidcBundle\OidcClient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/openid", name="")
 */
class OpenIDController extends AbstractController
{
    /**
     * This controller forward the user to the SURFconext login
     *
     * @Route("/login", name="login_openid")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     *
     * @param SessionInterface $session
     * @param OidcClient       $oidc
     *
     * @return RedirectResponse
     *
     * @throws OidcConfigurationException
     * @throws OidcConfigurationResolveException
     */
    public function loginOpenID(SessionInterface $session, OidcClient $oidc): RedirectResponse
    {
        // Remove errors from state
        $session->remove(Security::AUTHENTICATION_ERROR);
        $session->remove(Security::LAST_USERNAME);

        // Redirect to authorization @ surfconext
        return $oidc->generateAuthorizationRedirect(null, ['openid', 'profile', 'email']);
    }
    
    /**
     * This route handles every login request
     * Only this route is listened to by the security services, so another route is not possible
     *
     * @Route("/login_check", name="login_check")
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     */
    public function checkLogin(): RedirectResponse
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('api_v1get_user'));
        } else {
            return $this->redirect($this->generateUrl('login'));
        }
    }
}
