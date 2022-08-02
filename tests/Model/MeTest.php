<?php

namespace CommunityDS\Deputy\Api\Tests\Model;

use CommunityDS\Deputy\Api\Tests\Adapter\MockClient;
use CommunityDS\Deputy\Api\Tests\TestCase;

class MeTest extends TestCase
{
    public function testSchema()
    {

        $schema = $this->wrapper()->schema->resource('Me');
        $this->assertEquals('me', $schema->route('me'));
        $this->assertNull($schema->route());
    }

    public function testModel()
    {

        $me = $this->wrapper()->getMe();
        $this->assertNotNull($me);
        $this->assertEquals('First', $me->firstName);

        $this->assertEquals(MockClient::COMPANY_FIRST, $me->company);
        $this->assertFalse($me->isRelationPopulated('companyObject'));
        $this->assertNotNull($me->companyObject);
        $this->assertTrue($me->isRelationPopulated('companyObject'));
        $this->assertInstanceOf('CommunityDS\Deputy\Api\Model\Company', $me->companyObject);
        $this->assertEquals('First Company', $me->companyObject->companyName);
    }
}
