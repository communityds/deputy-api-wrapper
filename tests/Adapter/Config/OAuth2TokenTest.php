<?php

namespace CommunityDS\Deputy\Api\Tests\Adapter\Config;

use CommunityDS\Deputy\Api\Adapter\Config\OAuth2Token;
use CommunityDS\Deputy\Api\Tests\TestCase;

class OAuth2TokenTest extends TestCase
{
    protected function getAuthConfig()
    {
        return [
            'class' => 'CommunityDS\Deputy\Api\Adapter\Config\OAuth2Token',
            'clientId' => '8c5e9f31e8f9add76698ebb164dc5dc1',
            'clientSecret' => 'b3e32ab7b3f22e3d5b36d1099f30cda0',
            'redirectUri' => 'https://example.org',
        ];
    }

    /**
     * @return OAuth2Token
     */
    protected function auth()
    {
        return $this->wrapper()->auth;
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

        $accessToken = 'bb025805e7f1c362d8502540c5410ea4';
        $refreshToken = '790efd27619171636d49a03fd24f226a';

        $token = $this->auth()->verifyCode('d0d4eb04a4448bfbea671e7b19759e956e07472a');
        $this->assertNotNull($token);
        $this->assertEquals($accessToken, $token->getAccessToken(), 'Access token not as expected');
        $this->assertEquals($refreshToken, $token->getRefreshToken(), 'Refresh token not as expected');
        $this->assertGreaterThan(time() + 86000, $token->getExpires(), 'Expiry time is not as expected');

        $this->assertEquals($accessToken, $this->auth()->getToken(), 'Access token not as expected');

        $accessToken = '71f37de97c34bf379f5ac581f2a833fd';
        $refreshToken = '19df682ba5db7c613acb0b30a0a2513e';

        $token = $this->auth()->refreshToken($token);
        $this->assertNotNull($token);
        $this->assertEquals($accessToken, $token->getAccessToken(), 'Updated Access token not as expected');
        $this->assertEquals($refreshToken, $token->getRefreshToken(), 'Updated Refresh token not as expected');
        $this->assertLessThan(time(), $token->getExpires(), 'Updated Expiry time should automatically expire');

        $accessToken = '4ef08849d122ba5c9710768b99bbb0ea';
        $refreshToken = 'd5a3664b7989598d5ac62dd5069c26ed';

        $this->assertEquals($accessToken, $this->auth()->getToken(), 'Access token should change as previous token expired');
        $this->assertEquals($refreshToken, $this->auth()->getAccessToken()->getRefreshToken(), 'Refresh token not as expected');
    }

    public function testLoginCallback()
    {
        $this->wrapper()->auth->setAuthenticateHandler(
            function () {
                $this->auth()->verifyCode('d0d4eb04a4448bfbea671e7b19759e956e07472a');
            }
        );

        $this->assertNull($this->auth()->getAccessToken(), 'OAuth process not completed so there is no token');

        $accessToken = 'bb025805e7f1c362d8502540c5410ea4';
        $this->assertEquals($accessToken, $this->auth()->getToken(), 'Access token should change as previous token expired');
    }
}
