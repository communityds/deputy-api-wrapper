<?php

namespace CommunityDS\Deputy\Api\Tests\Schema\DataType;

use CommunityDS\Deputy\Api\Schema\DataType\Integer;
use CommunityDS\Deputy\Api\Tests\TestCase;

class IntegerTest extends TestCase
{
    protected function dataType()
    {
        return new Integer();
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
        $this->assertDataType(-100, $dataType->fromApi(-100));
        $this->assertDataType(0, $dataType->fromApi(0));
        $this->assertDataType(100, $dataType->fromApi(100));
        $this->assertDataType(-100, $dataType->fromApi('-100'));
        $this->assertDataType(0, $dataType->fromApi('0'));
        $this->assertDataType(100, $dataType->fromApi('100'));
    }

    public function testToApi()
    {
        $dataType = $this->dataType();
        $this->assertDataType(null, $dataType->toApi(null));
        $this->assertDataType(-100, $dataType->toApi(-100));
        $this->assertDataType(0, $dataType->toApi(0));
        $this->assertDataType(100, $dataType->toApi(100));
        $this->assertDataType(-100, $dataType->toApi('-100'));
        $this->assertDataType(0, $dataType->toApi('0'));
        $this->assertDataType(100, $dataType->toApi('100'));
    }
}
