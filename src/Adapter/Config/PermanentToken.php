<?php

namespace CommunityDS\Deputy\Api\Adapter\Config;

use CommunityDS\Deputy\Api\Adapter\AuthenticationInterface;
use CommunityDS\Deputy\Api\Component;

/**
 * Authentication using the Permanent Token.
 *
 * @see https://www.deputy.com/api-doc/API/Authentication
 */
class PermanentToken extends Component implements AuthenticationInterface
{
    /**
     * Authentication token.
     *
     * @var string
     */
    private $_token;

    /**
     * Updates the authentication token.
     *
     * @param string $token Token value
     *
     * @return void
     */
    public function setToken($token)
    {
        $this->_token = $token;
    }

    public function getToken()
    {
        return $this->_token;
    }
}
