<?php

namespace CommunityDS\Deputy\Api\Tests\Schema\DataType;

use CommunityDS\Deputy\Api\Schema\DataType\ArrayType;
use CommunityDS\Deputy\Api\Tests\TestCase;

class ArrayTest extends TestCase
{
    protected function dataType()
    {
        return new ArrayType();
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
        $this->assertDataType([], $dataType->fromApi(''));
        $this->assertDataType(['one', 'two', 'three'], $dataType->fromApi(['one', 'two', 'three']));
        $this->assertDataType(['uno' => 'one', 'due' => 'two'], $dataType->fromApi(['uno' => 'one', 'due' => 'two']));
    }

    public function testToApi()
    {
        $dataType = $this->dataType();
        $this->assertDataType(null, $dataType->toApi(null));
        $this->assertDataType([], $dataType->toApi([]));
        $this->assertDataType(['one', 'two', 'three'], $dataType->toApi(['one', 'two', 'three']));
        $this->assertDataType(['uno' => 'one', 'due' => 'two'], $dataType->toApi(['uno' => 'one', 'due' => 'two']));
    }
}
