<?php

namespace CommunityDS\Deputy\Api\Tests\Schema\DataType;

use CommunityDS\Deputy\Api\Schema\DataType\Json;
use CommunityDS\Deputy\Api\Tests\TestCase;

class JsonTest extends TestCase
{
    protected function dataType()
    {
        return new Json();
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
        $this->assertDataType([], $dataType->fromApi('{}'));
        $this->assertDataType([1, 2, 3], $dataType->fromApi('[1,2,3]'));
        $this->assertDataType(['a' => 1, 'b' => 2, 'c' => 3], $dataType->fromApi('{"a":1,"b":2,"c":3}'));
    }

    public function testToApi()
    {
        $dataType = $this->dataType();
        $this->assertDataType(null, $dataType->toApi(null));
        $this->assertDataType('[]', $dataType->toApi([]));
        $this->assertDataType('[1,2,3]', $dataType->toApi([1, 2, 3]));
        $this->assertDataType('{"a":1,"b":2,"c":3}', $dataType->toApi(['a' => 1, 'b' => 2, 'c' => 3]));
    }
}
