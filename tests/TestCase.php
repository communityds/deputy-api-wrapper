<?php

namespace CommunityDS\Deputy\Api\Tests;

use CommunityDS\Deputy\Api\Wrapper;

/**
 * Base test case class that provides helper functions.
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function tearDown()
    {
        parent::tearDown();
        Wrapper::setInstance(null);
    }

    /**
     * Returns the configuration of the authentication component.
     *
     * @return array
     */
    protected function getAuthConfig()
    {
        return [
            'class' => 'CommunityDS\Deputy\Api\Adapter\Config\PermanentToken',
            'token' => $this->getAuthToken(),
        ];
    }

    /**
     * Returns the authentication token.
     *
     * @return string
     */
    protected function getAuthToken()
    {
        return 'e9d60c4e518815fc92430bc76deb7567';
    }

    /**
     * Returns the API domain.
     *
     * @return string
     */
    protected function getApiDomain()
    {
        return 'sandbox.au.deputy.com';
    }

    /**
     * Returns the wrapper configuration array.
     *
     * @return array
     */
    protected function getWrapperConfig()
    {
        return [
            'auth' => $this->getAuthConfig(),
            'client' => 'CommunityDS\Deputy\Api\Tests\Adapter\MockClient',
            'persistent' => 'CommunityDS\Deputy\Api\Adapter\Native\RuntimeCache',
            'runtime' => 'CommunityDS\Deputy\Api\Adapter\Native\RuntimeCache',
            'target' => [
                'class' => 'CommunityDS\Deputy\Api\Adapter\Config\TargetConfig',
                'domain' => $this->getApiDomain(),
            ],
        ];
    }

    /**
     * Returns the current instance of the wrapper.
     *
     * @return Wrapper
     */
    protected function wrapper()
    {
        $instance = Wrapper::getInstance();
        if ($instance == null) {
            $instance = Wrapper::setInstance($this->getWrapperConfig());
        }
        return $instance;
    }

    /**
     * Returns the current instance of the API client.
     *
     * @return \CommunityDS\Deputy\Api\Tests\Adapter\MockClient
     */
    protected function client()
    {
        return $this->wrapper()->client;
    }

    /**
     * Returns a specific resource from the wrapper.
     *
     * @param string $name Resource name
     *
     * @return \CommunityDS\Deputy\Api\Schema\ResourceInfo
     */
    protected function resource($name)
    {
        return $this->wrapper()->schema->resource($name);
    }

    /**
     * Clears the request log.
     *
     * @return void
     */
    public function clearRequestLog()
    {
        $this->client()->resetLog();
    }

    /**
     * Asserts that the API requests match.
     *
     * @param array $expected Expected requests
     * @param string $message Message if fails
     *
     * @return void
     */
    public function assertRequestLog($expected, $message = '')
    {
        $this->assertEquals($expected, $this->client()->getLog(), $message);
    }
}
