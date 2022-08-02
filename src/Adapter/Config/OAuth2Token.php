<?php

namespace CommunityDS\Deputy\Api\Adapter\Config;

use Closure;
use CommunityDS\Deputy\Api\Adapter\AuthenticationInterface;
use CommunityDS\Deputy\Api\Component;
use CommunityDS\Deputy\Api\WrapperLocatorTrait;

/**
 * Authentication using the OAuth token.
 *
 * @see https://www.deputy.com/api-doc/API/Authentication
 */
class OAuth2Token extends Component implements AuthenticationInterface
{
    use WrapperLocatorTrait;

    /**
     * Access token.
     *
     * @var OAuth2AccessToken
     */
    private $_accessToken;

    /**
     * Client id
     *
     * @var string
     */
    private $_clientId;

    /**
     * Client secret.
     *
     * @var string
     */
    private $_clientSecret;

    /**
     * Redirection Uri.
     *
     * @var string
     */
    private $_redirectUri;

    /**
     * Indicates if long-life refresh tokens should be used.
     *
     * @var boolean
     */
    private $_longLife = true;

    /**
     * Callback to perform the authentication and return the access token.
     *
     * @var Closure
     */
    private $_authHandler;

    /**
     * Returns the client ID.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->_clientId;
    }

    /**
     * Sets the client ID.
     *
     * @param string $id
     *
     * @return $this
     */
    public function setClientId($id)
    {
        $this->_clientId = $id;
        return $this;
    }

    /**
     * Returns the client secret.
     *
     * @return string
     */
    public function getClientSecret()
    {
        return $this->_clientSecret;
    }

    /**
     * Sets the client secret.
     *
     * @param string $secret
     *
     * @return $this
     */
    public function setClientSecret($secret)
    {
        $this->_clientSecret = $secret;
        return $this;
    }

    /**
     * Returns the redirect URI.
     *
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->_redirectUri;
    }

    /**
     * Sets the redirect URI.
     *
     * @param string $uri
     *
     * @return $this
     */
    public function setRedirectUri($uri)
    {
        $this->_redirectUri = $uri;
        return $this;
    }

    /**
     * Returns the access token.
     *
     * @return OAuth2AccessToken
     */
    public function getAccessToken()
    {
        return $this->_accessToken;
    }

    /**
     * Sets the access token.
     *
     * @param OAuth2AccessToken $token Access value
     *
     * @return $this
     */
    public function setAccessToken($token)
    {
        $this->_accessToken = $token;
        return $this;
    }

    /**
     * Indicates if long-life refresh tokens will be requested.
     *
     * @return boolean
     */
    public function isLongLife()
    {
        return $this->_longLife;
    }

    /**
     * Indicates if long life refresh tokens should be created.
     *
     * @param boolean $longLife
     *
     * @return $this
     */
    public function setLongLife($longLife)
    {
        $this->_longLife = $longLife == true;
        return $this;
    }

    /**
     * Registers the callback that will be called if there is no access token.
     *
     * @param Closure $callback
     *
     * @return $this
     */
    public function setAuthenticateHandler($callback)
    {
        $this->_authHandler = $callback;
        return $this;
    }

    /**
     * Returns the URL used in the first step of the OAuth authentication process.
     *
     * @return string
     */
    public function getAuthenticateUrl()
    {
        return 'https://once.deputy.com/my/oauth/login?' .
            http_build_query(
                [
                    'client_id' => $this->getClientId(),
                    'redirect_uri' => $this->getRedirectUri(),
                    'response_type' => 'code',
                    'scope' => $this->_longLife ? 'longlife_refresh_token' : '',
                ]
            );
    }

    /**
     * Verifies the code from the authentication process and updates the
     * current access token.
     *
     * @param string $code
     *
     * @return OAuth2AccessToken|null
     */
    public function verifyCode($code)
    {
        $this->_accessToken = $this->getTokenInternal(
            'https://once.deputy.com/my/oauth/access_token',
            [
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'redirect_uri' => $this->getRedirectUri(),
                'grant_type' => 'authorization_code',
                'code' => $code,
                'scope' => $this->_longLife ? 'longlife_refresh_token' : '',
            ]
        );

        return $this->_accessToken;
    }

    /**
     * Refreshes the current access token using the refresh token.
     *
     * @param OAuth2AccessToken|null $token
     *
     * @return OAuth2AccessToken|null
     */
    public function refreshToken($token)
    {
        $this->_accessToken = $this->getTokenInternal(
            $this->getWrapper()->target->getOAuth2AccessTokenUrl(),
            [
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'redirect_uri' => $this->getRedirectUri(),
                'grant_type' => 'refresh_token',
                'refresh_token' => $token ? $token->getRefreshToken() : $this->getAccessToken()->getRefreshToken(),
                'scope' => $this->_longLife ? 'longlife_refresh_token' : '',
            ]
        );

        return $this->_accessToken;
    }

    /**
     * Sends a request to the OAuth endpoints.
     *
     * @param string $uri
     * @param array $params
     *
     * @return OAuth2AccessToken
     */
    protected function getTokenInternal($uri, $params)
    {
        $response = $this->getWrapper()->client->postOAuth2($uri, $params);
        if ($response) {
            return OAuth2AccessToken::createFromResponse($response);
        }
        return null;
    }

    public function getToken()
    {
        $accessToken = $this->getAccessToken();

        if ($accessToken == null && $this->_authHandler) {
            call_user_func($this->_authHandler);
            $accessToken = $this->getAccessToken();
        }

        if (!$accessToken->expires(60)) {
            $accessToken = $this->refreshToken($accessToken);
            if ($accessToken) {
                $this->setAccessToken($accessToken);
            }
        }

        if ($accessToken == null) {
            return null;
        }

        return $accessToken->getAccessToken();
    }
}
