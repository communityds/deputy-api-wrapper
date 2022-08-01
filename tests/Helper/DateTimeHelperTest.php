<?php

namespace CommunityDS\Deputy\Api\Tests\Helper;

use CommunityDS\Deputy\Api\Helper\DateTimeHelper;
use CommunityDS\Deputy\Api\Tests\TestCase;

class DateTimeHelperTest extends TestCase
{
    public function testGetMidnight()
    {
        $expected = new \DateTime('2018-01-01 00:00:00', new \DateTimeZone('Australia/Adelaide'));
        $actual = DateTimeHelper::getMidnight(
            new \DateTime('2018-01-01 12:00:00', new \DateTimeZone('Australia/Adelaide'))
        );
        $this->assertEquals(
            $expected->format(\DateTime::ATOM),
            $actual->format(\DateTime::ATOM)
        );
        $this->assertEquals(
            $expected->getTimestamp(),
            $actual->getTimestamp()
        );
    }

    public function testGetSecondsFromMidnight()
    {
        $this->assertEquals(
            (1 * 60 * 60) + (24 * 60) + 56,
            DateTimeHelper::getSecondsFromMidnight(
                new \DateTime('2018-01-01 01:24:56', new \DateTimeZone('Australia/Adelaide'))
            )
        );
    }

    public function testGetMinutesFromMidnight()
    {
        $this->assertEquals(
            (1 * 60) + 24,
            DateTimeHelper::getMinutesFromMidnight(
                new \DateTime('2018-01-01 01:24:56', new \DateTimeZone('Australia/Adelaide'))
            )
        );
        $this->assertEquals(
            (1 * 60) + 24,
            DateTimeHelper::getMinutesFromMidnight(
                new \DateTime('2018-01-01 01:24:56', new \DateTimeZone('Australia/Adelaide')),
                true
            )
        );
        $this->assertEquals(
            (1 * 60) + 25,
            DateTimeHelper::getMinutesFromMidnight(
                new \DateTime('2018-01-01 01:24:56', new \DateTimeZone('Australia/Adelaide')),
                false
            )
        );
    }
}
