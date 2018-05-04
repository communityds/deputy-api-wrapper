<?php

namespace CommunityDS\Deputy\Api\Tests\Helper;

use CommunityDS\Deputy\Api\Helper\ClientHelper;
use CommunityDS\Deputy\Api\Tests\TestCase;

class ClientHelperTest extends TestCase
{

    protected function valueSerialized()
    {
        return '{"null":null,"string":"value","integer":1,"float":1.23,"boolean":true,"array":[1,2],"object":{"first":1,"second":2}}';
    }

    protected function valueUnserialized()
    {
        return [
            'null' => null,
            'string' => 'value',
            'integer' => 1,
            'float' => 1.23,
            'boolean' => true,
            'array' => [1, 2],
            'object' => ['first' => 1, 'second' => 2],
        ];
    }

    public function testSerialize()
    {
        $this->assertEquals(
            $this->valueSerialized(),
            ClientHelper::serialize($this->valueUnserialized())
        );
        $this->assertSame(
            $this->valueSerialized(),
            ClientHelper::serialize($this->valueUnserialized())
        );
    }

    public function testUnserialize()
    {
        $this->assertEquals(
            $this->valueUnserialized(),
            ClientHelper::unserialize($this->valueSerialized())
        );
        $this->assertSame(
            $this->valueUnserialized(),
            ClientHelper::unserialize($this->valueSerialized())
        );
    }

    public function testCheckResponse()
    {
        $this->assertNull(
            ClientHelper::checkResponse(null)
        );
        $this->assertNull(
            ClientHelper::checkResponse('')
        );
        $this->assertNull(
            ClientHelper::checkResponse('Unexpected error')
        );
        $this->assertNull(
            ClientHelper::checkResponse(['other' => 'value'])
        );

        $this->setExpectedException(
            'CommunityDS\Deputy\Api\DeputyException',
            'Not Found',
            404
        );
        $this->assertNull(
            ClientHelper::checkResponse(
                [
                    'error' => [
                        'message' => 'Not Found',
                        'code' => 404,
                    ],
                ]
            )
        );
    }
}
