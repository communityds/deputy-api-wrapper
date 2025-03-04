<?php

namespace CommunityDS\Deputy\Api\Tests\Model;

use CommunityDS\Deputy\Api\Tests\Adapter\MockClient;
use CommunityDS\Deputy\Api\Tests\TestCase;

class CustomFieldDataTest extends TestCase
{
    public function testGet()
    {
        $record = $this->wrapper()->getCustomFieldData(MockClient::CUSTOM_FIELD_DATA_FIRST);

        $this->assertEquals(10, $record->f01); // traveldistance
        $this->assertEquals(20, $record->f02); // traveltime

        // Check apiname properties
        $this->assertEquals(10, $record->traveldistance);
        $this->assertEquals(20, $record->traveltime);

        // Ensure setting via apiname property also sets the f-slot
        $record->traveltime = 30;
        $this->assertEquals(30, $record->traveltime);
        $this->assertEquals(30, $record->f02);
    }
}
