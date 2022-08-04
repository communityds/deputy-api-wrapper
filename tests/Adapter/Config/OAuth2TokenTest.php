<?php

namespace CommunityDS\Deputy\Api\Tests\Adapter\Config;

use CommunityDS\Deputy\Api\Adapter\Config\OAuth2AccessToken;
use CommunityDS\Deputy\Api\Adapter\Config\OAuth2Token;
use CommunityDS\Deputy\Api\Tests\Adapter\MockClient;
use CommunityDS\Deputy\Api\Tests\TestCase;

class OAuth2TokenTest extends TestCase
{
    /**
     * @var boolean
     */
    protected $authenticate = false;

    /**
     * @var OAuth2AccessToken
     */
    protected $token;

    protected function getAuthConfig()
    {
        return [
            'class' => 'CommunityDS\Deputy\Api\Adapter\Config\OAuth2Token',
            'clientId' => '8c5e9f31e8f9add76698ebb164dc5dc1',
            'clientSecret' => 'b3e32ab7b3f22e3d5b36d1099f30cda0',
            'redirectUri' => 'https://example.org',
            'tokenProvider' => [
                'class' => 'CommunityDS\Deputy\Api\Adapter\Native\OAuth2AccessTokenProviderProxy',
                'provider' => $this,
            ],
        ];
    }

    /**
     * @return OAuth2Token
     */
    protected function auth()
    {
        return $this->wrapper()->auth;
    }

    public function getAccessToken()
    {
        if ($this->token == null && $this->authenticate) {
            $this->token = $this->auth()->verifyCode(MockClient::OAUTH_CODE_ACTIVE);
        }
        return $this->token;
    }

    public function setAccessToken($token)
    {
        $this->token = $token;
        return $this;
    }

    public function testOAuth2()
    {
        $this->assertEquals(
            'https://once.deputy.com/my/oauth/login?' .
                'client_id=8c5e9f31e8f9add76698ebb164dc5dc1' .
                '&redirect_uri=https%3A%2F%2Fexample.org' .
                '&response_type=code' .
                '&scope=longlife_refresh_token',
            $this->auth()->getAuthenticateUrl()
        );

        $this->assertNull($this->auth()->getAccessToken(), 'OAuth process not completed so there is no token');

        $token = $this->auth()->verifyCode(MockClient::OAUTH_CODE_ACTIVE);
        $this->assertNotNull($token);
        $this->assertEquals(MockClient::OAUTH_TOKEN_ACTIVE, $token->getAccessToken(), 'Access token not as expected');
        $this->assertEquals(MockClient::OAUTH_REFRESH_EXPIRED, $token->getRefreshToken(), 'Refresh token not as expected');
        $this->assertGreaterThan(time() + 86000, $token->getExpires(), 'Expiry time is not as expected');

        $this->assertEquals(MockClient::OAUTH_TOKEN_ACTIVE, $this->auth()->getToken(), 'Access token not as expected');

        $token = $this->auth()->refreshToken($token);
        $this->assertNotNull($token);
        $this->assertEquals(MockClient::OAUTH_TOKEN_EXPIRED, $token->getAccessToken(), 'Updated Access token not as expected');
        $this->assertEquals(MockClient::OAUTH_REFRESH_ACTIVE, $token->getRefreshToken(), 'Updated Refresh token not as expected');
        $this->assertLessThan(time(), $token->getExpires(), 'Updated Expiry time should automatically expire');

        $this->assertEquals(MockClient::OAUTH_TOKEN_ACTIVE, $this->auth()->getToken(), 'Access token should change as previous token expired');
        $this->assertEquals(MockClient::OAUTH_REFRESH_EXPIRED, $this->auth()->getAccessToken()->getRefreshToken(), 'Refresh token not as expected');
    }

    public function testLoginAuthenticate()
    {
        $this->authenticate = true;
        $this->assertEquals(MockClient::OAUTH_TOKEN_ACTIVE, $this->auth()->getToken(), 'Access token should change as previous token expired');
    }
}
