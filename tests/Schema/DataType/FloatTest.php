<?php

namespace CommunityDS\Deputy\Api\Tests\Schema\DataType;

use CommunityDS\Deputy\Api\Schema\DataType\Float;
use CommunityDS\Deputy\Api\Tests\TestCase;

class FloatTest extends TestCase
{

    protected function dataType()
    {
        return new Float();
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
        $this->assertDataType(-100.123, $dataType->fromApi(-100.123));
        $this->assertDataType(0.0, $dataType->fromApi(0.0));
        $this->assertDataType(100.123, $dataType->fromApi(100.123));
        $this->assertDataType(-100.123, $dataType->fromApi('-100.123'));
        $this->assertDataType(0.0, $dataType->fromApi('0.0'));
        $this->assertDataType(100.123, $dataType->fromApi('100.123'));
    }

    public function testToApi()
    {
        $dataType = $this->dataType();
        $this->assertDataType(null, $dataType->toApi(null));
        $this->assertDataType(-100.123, $dataType->toApi(-100.123));
        $this->assertDataType(0.0, $dataType->toApi(0.0));
        $this->assertDataType(100.123, $dataType->toApi(100.123));
        $this->assertDataType(-100.123, $dataType->toApi('-100.123'));
        $this->assertDataType(0.0, $dataType->toApi('0'));
        $this->assertDataType(100.123, $dataType->toApi('100.123'));
    }
}
