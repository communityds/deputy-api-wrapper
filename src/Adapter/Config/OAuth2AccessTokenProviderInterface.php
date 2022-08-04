<?php

namespace CommunityDS\Deputy\Api\Adapter\Config;

/**
 * Provides an interface used by the OAuth 2.0 Authentication component
 * to retrieve or persist tokens from a storage medium implemented by
 * the application.
 */
interface OAuth2AccessTokenProviderInterface
{
    /**
     * Returns the current access token or if there is no token
     * begins the OAuth 2.0 Authentication process.
     *
     * @return OAuth2AccessToken|null The current token; or null if
     *         there is no token or the authentication process has begun
     */
    public function getAccessToken();

    /**
     * Changes the current access token.
     *
     * @param OAuth2AccessToken|null $token The new token; or null
     *        if logged out or the token is no longer valid
     */
    public function setAccessToken($token);
}
