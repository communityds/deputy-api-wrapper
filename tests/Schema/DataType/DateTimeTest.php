<?php

namespace CommunityDS\Deputy\Api\Tests\Schema\DataType;

use CommunityDS\Deputy\Api\Schema\DataType\DateTime;
use CommunityDS\Deputy\Api\Tests\TestCase;

class DateTimeTest extends TestCase
{
    protected function dataType()
    {
        return new DateTime();
    }

    protected function assertDataType($expected, $actual, $message = '')
    {
        if ($actual === null || is_string($expected)) {
            $this->assertEquals($expected, $actual, $message);
            $this->assertSame($expected, $actual, $message);
        } else {
            $this->assertTrue($actual instanceof \DateTime);
            $this->assertEquals($expected->getTimestamp(), $actual->getTimestamp(), $message);
        }
    }

    public function testFromApi()
    {
        $dataType = $this->dataType();
        $this->assertDataType(null, $dataType->fromApi(null));
        $this->assertDataType(
            new \DateTime('2018-04-27T16:15:25', new \DateTimeZone('Australia/Adelaide')),
            $dataType->fromApi('2018-04-27T16:15:25+09:30')
        );
    }

    public function testToApi()
    {
        $dataType = $this->dataType();
        $this->assertDataType(null, $dataType->toApi(null));
        $this->assertDataType(
            '2018-04-27T16:15:25+09:30',
            $dataType->toApi(new \DateTime('2018-04-27T16:15:25', new \DateTimeZone('Australia/Adelaide')))
        );
    }
}
