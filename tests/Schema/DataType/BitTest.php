<?php

namespace CommunityDS\Deputy\Api\Tests\Schema\DataType;

use CommunityDS\Deputy\Api\Schema\DataType\Bit;
use CommunityDS\Deputy\Api\Tests\TestCase;

class BitTest extends TestCase
{
    protected function dataType()
    {
        return new Bit();
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
        $this->assertDataType(false, $dataType->fromApi(false));
        $this->assertDataType(true, $dataType->fromApi(true));
    }

    public function testToApi()
    {
        $dataType = $this->dataType();
        $this->assertDataType(null, $dataType->toApi(null));
        $this->assertDataType(false, $dataType->toApi(false));
        $this->assertDataType(true, $dataType->toApi(true));
    }
}
