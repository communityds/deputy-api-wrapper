<?php

namespace CommunityDS\Deputy\Api\Tests\Schema\DataType;

use CommunityDS\Deputy\Api\Schema\DataType\VarCharArray;
use CommunityDS\Deputy\Api\Tests\TestCase;

class VarCharArrayTest extends TestCase
{

    protected function dataType()
    {
        return new VarCharArray();
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
        $this->assertDataType(
            ['one', 'two', 'three'],
            $dataType->fromApi(['one', 'two', 'three'])
        );
    }

    public function testToApi()
    {
        $dataType = $this->dataType();
        $this->assertDataType(null, $dataType->toApi(null));
        $this->assertDataType(
            ['one', 'two', 'three'],
            $dataType->toApi(['one', 'two', 'three'])
        );
    }
}
