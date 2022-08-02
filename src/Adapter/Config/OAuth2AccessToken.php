<?php

namespace CommunityDS\Deputy\Api\Adapter\Config;

use CommunityDS\Deputy\Api\Component;

class OAuth2AccessToken extends Component
{
    /**
     * Access token.
     *
     * @var string
     */
    private $_accessToken;

    /**
     * Refresh token.
     *
     * @var string
     */
    private $_refreshToken;

    /**
     * Timestamp of when token expires.
     *
     * @var integer
     */
    private $_expires;

    /**
     * Returns the access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->_accessToken;
    }

    /**
     * Returns the UNIX timestamp of when this token will expire.
     *
     * @return integer
     */
    public function getExpires()
    {
        return $this->_expires;
    }

    /**
     * Indicates if this token will expire soon.
     *
     * @param integer $window
     *
     * @return boolean
     */
    public function expires($window = 0)
    {
        return $this->_expires > (time() - $window);
    }

    /**
     * Returns the refresh token.
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->_refreshToken;
    }

    /**
     * Creates a new token from the response.
     *
     * @param array $response
     *
     * @return static
     */
    public static function createFromResponse($response)
    {
        $token = new static();
        $token->_accessToken = isset($response['access_token']) ? $response['access_token'] : null;
        $token->_refreshToken = isset($response['refresh_token']) ? $response['refresh_token'] : null;
        $token->_expires = isset($response['expires_in']) ? (time() + $response['expires_in']) : null;
        return $token;
    }
}
