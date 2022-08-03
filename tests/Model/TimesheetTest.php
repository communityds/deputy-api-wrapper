<?php

namespace CommunityDS\Deputy\Api\Tests\Model;

use CommunityDS\Deputy\Api\NotSupportedException;
use CommunityDS\Deputy\Api\Tests\Adapter\MockClient;
use CommunityDS\Deputy\Api\Tests\TestCase;

class TimesheetTest extends TestCase
{
    public function testCreate()
    {
        $startTime = new \DateTime('2018-01-01 09:00:00', new \DateTimeZone('Australia/Adelaide'));
        $endTime = new \DateTime('2018-01-01 11:00:00', new \DateTimeZone('Australia/Adelaide'));

        $timesheet = $this->wrapper()->createTimesheet();
        $timesheet->employee = MockClient::EMPLOYEE_FIRST;
        $timesheet->operationalUnit = MockClient::OP_UNIT_FIRST;
        $timesheet->startTime = $startTime;
        $timesheet->endTime = $endTime;
        $timesheet->mealbreakMinutes = 30;
        $this->assertTrue($timesheet->isAttributeDirty('startTime'));
        $this->assertTrue($timesheet->save());

        $this->assertEquals(MockClient::TIMESHEET_NEW, $timesheet->id);
        $this->assertEquals(
            $startTime->getTimestamp(),
            $timesheet->startTime->getTimestamp()
        );
        $this->assertEquals(
            $endTime->getTimestamp(),
            $timesheet->endTime->getTimestamp()
        );
        $this->assertFalse($timesheet->isAttributeDirty('startTime'));

        $this->assertRequestLog(
            [
                ['get' => 'resource/Timesheet/INFO'],
                ['post' => 'supervise/timesheet/update'],
            ]
        );
    }

    public function testUpdate()
    {
        $this->expectException(NotSupportedException::class);

        $timesheet = $this->wrapper()->getTimesheet(MockClient::TIMESHEET_NEW);
        $this->assertFalse($timesheet->save());
    }
}
