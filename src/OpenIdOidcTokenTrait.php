<?php

namespace App;

use Drenso\OidcBundle\Security\Authentication\Token\OidcToken;

trait OpenIdOidcTokenTrait
{
    /**
     * The sub has a format like "google-oauth2|217699422879603970458" and there are some characters that cannot be
     * assigned to a php variable name
     *
     * @param OidcToken $oidcToken
     *
     * @return array|string|string[]|null
     */
    public function getUsernameFromSub(OidcToken $oidcToken)
    {
        return preg_replace("/[^A-Za-z0-9]/", '', $oidcToken->getSub());
    }
}
