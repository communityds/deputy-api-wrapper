<?php

namespace CommunityDS\Deputy\Api\Tests\Model;

use CommunityDS\Deputy\Api\Tests\Adapter\MockClient;
use CommunityDS\Deputy\Api\Tests\TestCase;

class UserTest extends TestCase
{
    protected function schema()
    {
        return $this->wrapper()->schema->resource('User');
    }

    public function testSchema()
    {

        $schema = $this->schema();
        $this->assertEquals('userinfo/123', $schema->route(123));
        $this->assertNull($schema->route());
    }

    public function testModel()
    {

        $schema = $this->schema();

        $user = $schema->findOne(MockClient::USER_ADMIN);
        $this->assertNotNull($user);
        $this->assertEquals($user->id, MockClient::USER_ADMIN);

        $user = $schema->findOne(MockClient::USER_FIRST);
        $this->assertNotNull($user);
        $this->assertEquals($user->id, MockClient::USER_FIRST);
        $this->assertEquals('First User', $user->displayName);
    }
}
