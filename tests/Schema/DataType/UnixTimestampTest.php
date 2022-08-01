<?php

namespace CommunityDS\Deputy\Api\Tests\Schema\DataType;

use CommunityDS\Deputy\Api\Schema\DataType\UnixTimestamp;
use CommunityDS\Deputy\Api\Tests\TestCase;

class UnixTimestampTest extends TestCase
{
    protected function dataType()
    {
        return new UnixTimestamp();
    }

    protected function assertDataType($expected, $actual, $message = '')
    {
        if ($actual === null || is_numeric($expected)) {
            $this->assertEquals($expected, $actual, $message);
            $this->assertSame($expected, $actual, $message);
        } elseif ($expected instanceof \DateTime) {
            $this->assertTrue($actual instanceof \DateTime);
            $this->assertEquals($expected->getTimestamp(), $actual->getTimestamp(), $message);
        } else {
            $this->assertFalse(true, 'Unexpected data type');
        }
    }

    public function testFromApi()
    {
        $dataType = $this->dataType();
        $this->assertDataType(null, $dataType->fromApi(null));
        $this->assertDataType(
            new \DateTime('2018-04-27T16:15:25', new \DateTimeZone('Australia/Adelaide')),
            $dataType->fromApi(1524811525)
        );
    }

    public function testToApi()
    {
        $dataType = $this->dataType();
        $this->assertDataType(null, $dataType->toApi(null));
        $this->assertDataType(
            1524811525,
            $dataType->toApi(1524811525)
        );
        $this->assertDataType(
            1524811525,
            $dataType->toApi(new \DateTime('2018-04-27T16:15:25', new \DateTimeZone('Australia/Adelaide')))
        );
    }
}
