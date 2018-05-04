<?php

namespace CommunityDS\Deputy\Api\Tests\Model;

use CommunityDS\Deputy\Api\Tests\Adapter\MockClient;
use CommunityDS\Deputy\Api\Tests\TestCase;

class RosterTest extends TestCase
{

    public function testCreate()
    {

        $startTime = new \DateTime('2018-01-01 09:00:00', new \DateTimeZone('Australia/Adelaide'));
        $endTime = new \DateTime('2018-01-01 11:00:00', new \DateTimeZone('Australia/Adelaide'));
        $mealbreak = new \DateTime('2018-01-01 00:30:00', new \DateTimeZone('Australia/Adelaide'));

        $roster = $this->wrapper()->createRoster();
        $roster->employee = MockClient::EMPLOYEE_FIRST;
        $roster->operationalUnit = MockClient::OP_UNIT_FIRST;
        $roster->startTime = $startTime;
        $roster->endTime = $endTime;
        $roster->mealbreak = $mealbreak;
        $roster->published = true;
        $this->assertTrue($roster->isAttributeDirty('startTime'));
        $this->assertTrue($roster->save());

        $this->assertEquals(MockClient::ROSTER_NEW, $roster->id);
        $this->assertEquals(
            $startTime->getTimestamp(),
            $roster->startTime->getTimestamp()
        );
        $this->assertEquals(
            $endTime->getTimestamp(),
            $roster->endTime->getTimestamp()
        );
        $this->assertEquals(
            $mealbreak->getTimestamp(),
            $roster->mealbreak->getTimestamp()
        );
        $this->assertFalse($roster->isAttributeDirty('startTime'));

        $this->assertRequestLog(
            [
                ['get' => 'resource/Roster/INFO'],
                ['post' => 'supervise/roster'],
            ]
        );
    }

    public function testCreateFail()
    {
        $roster = $this->wrapper()->createRoster();
        $roster->employee = MockClient::EMPLOYEE_FIRST;
        $this->assertFalse($roster->save());
    }

    public function testUpdate()
    {

        $mealbreak = new \DateTime('2018-01-01 01:00:00', new \DateTimeZone('Australia/Adelaide'));

        $roster = $this->wrapper()->getRoster(MockClient::ROSTER_FIRST);
        $roster->mealbreak = $mealbreak;
        $roster->open = true;
        $roster->comment = 'Testing Comments';
        $this->assertTrue($roster->isAttributeDirty('comment'));
        $this->assertTrue($roster->save());

        $this->assertTrue($roster->open);
        $this->assertEquals('Testing Comments', $roster->comment);
        $this->assertEquals(
            $mealbreak->getTimestamp(),
            $roster->mealbreak->getTimestamp()
        );
        $this->assertFalse($roster->isAttributeDirty('comment'));

        $this->assertRequestLog(
            [
                ['get' => 'resource/Roster/INFO'],
                ['get' => 'resource/Roster/' . MockClient::ROSTER_FIRST],
                ['post' => 'supervise/roster'],
                ['post' => 'resource/Roster/' . MockClient::ROSTER_FIRST],
            ]
        );
    }

    public function testCreateUpdate()
    {

        $startTime = new \DateTime('2018-01-01 09:00:00', new \DateTimeZone('Australia/Adelaide'));
        $endTime = new \DateTime('2018-01-01 11:00:00', new \DateTimeZone('Australia/Adelaide'));
        $mealbreak = new \DateTime('2018-01-01 01:00:00', new \DateTimeZone('Australia/Adelaide'));

        $roster = $this->wrapper()->createRoster();
        $roster->employee = MockClient::EMPLOYEE_FIRST;
        $roster->operationalUnit = MockClient::OP_UNIT_FIRST;
        $roster->startTime = $startTime;
        $roster->endTime = $endTime;
        $roster->mealbreak = $mealbreak;
        $roster->published = true;
        $roster->open = true;
        $roster->comment = 'Testing Comments';
        $this->assertTrue($roster->isAttributeDirty('startTime'));
        $this->assertTrue($roster->save());

        $this->assertRequestLog(
            [
                ['get' => 'resource/Roster/INFO'],
                ['post' => 'supervise/roster'],
                ['post' => 'resource/Roster/' . MockClient::ROSTER_NEW],
            ]
        );
    }

    public function testMealbreakMinutes()
    {
        $roster = $this->wrapper()->getRoster(MockClient::ROSTER_FIRST);

        $mealbreak = $roster->mealbreak;
        $this->assertEquals(30, $roster->mealbreakMinutes);

        $roster->mealbreakMinutes = 60;
        $this->assertEquals(60, $roster->mealbreakMinutes);
    }
}
