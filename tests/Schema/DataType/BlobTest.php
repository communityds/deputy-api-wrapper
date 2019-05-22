<?php

namespace CommunityDS\Deputy\Api\Tests\Schema\DataType;

use CommunityDS\Deputy\Api\Schema\DataType\Blob;
use CommunityDS\Deputy\Api\Tests\TestCase;

class BlobTest extends TestCase
{

    protected function dataType()
    {
        return new Blob();
    }

    protected function assertDataType($expected, $actual, $message = '')
    {
        $this->assertEquals($expected, $actual, $message);
        $this->assertSame($expected, $actual, $message);
    }

    public function testFromApi()
    {
        $dataType = $this->dataType();
        $this->assertDataType(null, $dataType->fromApi(null));
        $this->assertDataType('', $dataType->fromApi(''));
        $this->assertDataType('0', $dataType->fromApi(0));
        $this->assertDataType('', $dataType->fromApi(false));
        $this->assertDataType('1', $dataType->fromApi(true));
        $this->assertDataType('Testing', $dataType->fromApi('Testing'));
        $this->assertDataType("Testing\nMulti-Line", $dataType->fromApi("Testing\nMulti-Line"));
    }

    public function testToApi()
    {
        $dataType = $this->dataType();
        $this->assertDataType(null, $dataType->toApi(null));
        $this->assertDataType('', $dataType->toApi(''));
        $this->assertDataType('0', $dataType->toApi(0));
        $this->assertDataType('', $dataType->toApi(false));
        $this->assertDataType('1', $dataType->toApi(true));
        $this->assertDataType('Testing', $dataType->toApi('Testing'));
        $this->assertDataType("Testing\nMulti-Line", $dataType->toApi("Testing\nMulti-Line"));
    }
}
