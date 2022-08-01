<?php

namespace CommunityDS\Deputy\Api\Tests\Adapter;

use CommunityDS\Deputy\Api\Adapter\ClientInterface;
use CommunityDS\Deputy\Api\Tests\TestCase;
use PHPUnit\Framework\Assert;

abstract class AdapterTestCase extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    protected function path($path)
    {
        $class = new \ReflectionClass($this);
        return dirname($class->getFileName()) . str_replace('/', DIRECTORY_SEPARATOR, '/' . $path);
    }

    protected function prepareEnvironment()
    {
        $path = $this->path('vendor/autoload.php');
        if (file_exists($path) == false) {
            Assert::markTestSkipped('Dependencies have not been installed');
            return false;
        }
        require_once $path;
        return true;
    }

    protected function config($key = null)
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
        if (file_exists($path)) {
            $config = include $path;
            if ($key) {
                return $config[$key];
            }
            return $config;
        }
        return null;
    }

    protected function getApiDomain()
    {
        return $this->config('domain');
    }

    protected function getAuthToken()
    {
        return $this->config('token');
    }

    protected function getWrapperConfig()
    {
        $config = parent::getWrapperConfig();
        $config['client'] = $this->getClientConfig();
        return $config;
    }

    public function testAdapter()
    {
        if ($this->prepareEnvironment() == false) {
            $this->markTestSkipped('Testing environment not loaded');
            return;
        }

        $client = $this->wrapper()->client;

        // GET method
        $result = $client->get('me');
        $expected = $this->config('me');
        $actual = [];
        foreach ($expected as $key => $value) {
            $actual[$key] = $result[$key];
        }
        $this->assertEquals($expected, $actual);
        $companyId = $result['Company'];
        $this->assertNotEquals(null, $companyId);

        // PUT method
        $result = $client->put(
            'supervise/memo',
            ['strContent' => 'Testing PUT method', 'intCompany' => $companyId]
        );
        $this->assertArrayHasKey('Id', $result);

        // POST method
        $result = $client->post(
            'supervise/employee',
            [
                'strFirstName' => 'POST',
                'strLastName' => 'Test',
                'intCompanyId' => $companyId,
            ]
        );
        $this->assertNotFalse($result, 'Possible that employee already exists. If so please manually terminate then delete');
        $this->assertArrayHasKey('Id', $result);
        $this->assertEquals('POST', $result['FirstName']);
        $employeeId = $result['Id'];

        // Terminate employee to allow deletion
        $result = $client->post(
            'supervise/employee/' . $employeeId . '/terminate',
            []
        );
        $this->assertNotFalse($result);

        // DELETE method
        $result = $client->delete(
            'resource/Employee/' . $employeeId
        );
        $this->assertNotFalse($result);
        $this->assertEquals('Deleted POST Test', $result);
    }

    /**
     * Returns configuration array for the `client` component on the wrapper.
     *
     * @return array
     */
    abstract protected function getClientConfig();
}
