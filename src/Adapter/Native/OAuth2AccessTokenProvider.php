<?php

namespace CommunityDS\Deputy\Api\Adapter\Native;

use CommunityDS\Deputy\Api\Adapter\Config\OAuth2AccessToken;
use CommunityDS\Deputy\Api\Adapter\Config\OAuth2AccessTokenProviderInterface;
use CommunityDS\Deputy\Api\Component;

/**
 * Simple implementation of the OAuth 2.0 Access Token provider that
 * gets or persists the token in memory but does not perform the OAuth 2.0
 * Authentication process.
 */
class OAuth2AccessTokenProvider extends Component implements OAuth2AccessTokenProviderInterface
{
    /**
     * @var OAuth2AccessToken
     */
    private $token;

    public function getAccessToken()
    {
        return $this->token;
    }

    public function setAccessToken($token)
    {
        $this->token = $token;
    }
}
