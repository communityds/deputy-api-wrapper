<?php

namespace CommunityDS\Deputy\Api\Adapter\Config;

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
     * Configuration or instance of the access token provider.
     *
     * @var OAuth2AccessTokenProviderInterface|array
     */
    public $tokenProvider = 'CommunityDS\Deputy\Api\Adapter\Native\OAuth2AccessTokenProvider';

    public function init()
    {
        parent::init();
        $this->tokenProvider = Component::createObject($this->tokenProvider);
    }

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
     * Returns the current access token from the token provider.
     *
     * @return OAuth2AccessToken|null
     */
    public function getAccessToken()
    {
        return $this->tokenProvider->getAccessToken();
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
        $token = $this->getTokenInternal(
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

        if ($token) {
            $this->tokenProvider->setAccessToken($token);
        }

        return $token;
    }

    /**
     * Refreshes the provided access token by using the refresh token to
     * request a new access token.
     *
     * @param OAuth2AccessToken $token
     *
     * @return OAuth2AccessToken|null
     */
    public function refreshToken($token)
    {
        $token = $this->getTokenInternal(
            $this->getWrapper()->target->getOAuth2AccessTokenUrl(),
            [
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'redirect_uri' => $this->getRedirectUri(),
                'grant_type' => 'refresh_token',
                'refresh_token' => $token ? $token->getRefreshToken() : $this->tokenProvider->getAccessToken()->getRefreshToken(),
                'scope' => $this->_longLife ? 'longlife_refresh_token' : '',
            ]
        );

        $this->tokenProvider->setAccessToken($token);

        return $token;
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
        $accessToken = $this->tokenProvider->getAccessToken();

        // Automatically refresh the token if it will expire soon
        if ($accessToken && !$accessToken->expires(60)) {
            $accessToken = $this->refreshToken($accessToken);
        }

        return $accessToken ? $accessToken->getAccessToken() : null;
    }
}
