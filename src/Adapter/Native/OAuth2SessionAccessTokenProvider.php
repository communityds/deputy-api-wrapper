<?php

namespace CommunityDS\Deputy\Api\Adapter\Native;

use CommunityDS\Deputy\Api\Adapter\Config\OAuth2AccessToken;
use CommunityDS\Deputy\Api\Adapter\Config\OAuth2AccessTokenProviderInterface;
use CommunityDS\Deputy\Api\Component;
use CommunityDS\Deputy\Api\WrapperLocatorTrait;

/**
 * Stores the access tokens in the native PHP session and redirects the
 * client to the OAuth 2.0 authenticate URL if there is no token.
 *
 * Note that this provider provides a very simple implementation that may
 * conflict with the application framework in use. As such it is strongly
 * recommended that a custom access token provider be created that conforms
 * to the application framework. This implementation could extend from this
 * class and overload the protected functions.
 */
class OAuth2SessionAccessTokenProvider extends Component implements OAuth2AccessTokenProviderInterface
{
    use WrapperLocatorTrait;

    /**
     * Returns the access token from the session.
     *
     * @return mixed|null
     */
    protected function retrieveToken()
    {
        return isset($_SESSION['deputy_token']) ? unserialize($_SESSION['deputy_token']) : null;
    }

    /**
     * Persists the access token to the session.
     *
     * @param OAuth2AccessToken|null $token
     */
    protected function persistToken($token)
    {
        if ($token) {
            $_SESSION['deputy_token'] = serialize($token);
        } else {
            unset($_SESSION['deputy_token']);
        }
    }

    /**
     * Redirects the user to begin the OAuth 2.0 process.
     *
     * @return OAuth2AccessToken|null
     */
    protected function authenticate()
    {
        header('Location: ' . $this->getWrapper()->auth->getAuthenticateUrl());
        return null;
    }

    public function getAccessToken()
    {
        $token = $this->retrieveToken();
        if ($token == null) {
            $token = $this->authenticate();
        }
        return $token;
    }

    public function setAccessToken($token)
    {
        $this->persistToken($token);
    }
}
