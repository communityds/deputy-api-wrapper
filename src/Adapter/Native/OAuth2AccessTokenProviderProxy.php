<?php

namespace CommunityDS\Deputy\Api\Adapter\Native;

use CommunityDS\Deputy\Api\Adapter\Config\OAuth2AccessTokenProviderInterface;
use CommunityDS\Deputy\Api\Component;

/**
 * Implementation of the OAuth 2.0 Access Token provider that acts as a
 * proxy to another token provider.
 *
 * An example is using this proxy when the application has created a Deputy
 * service provider to manage this wrapper. The service provider could
 * implement the `OAuth2AccessTokenProviderInterface` itself and then use
 * this proxy to reference itself. This removes the need to create
 * a dedicated access token provider class.
 */
class OAuth2AccessTokenProviderProxy extends Component implements OAuth2AccessTokenProviderInterface
{
    /**
     * @var OAuth2AccessTokenProviderInterface Provider instance
     */
    public $provider;

    public function getAccessToken()
    {
        return $this->provider->getAccessToken();
    }

    public function setAccessToken($token)
    {
        $this->provider->setAccessToken($token);
    }
}
