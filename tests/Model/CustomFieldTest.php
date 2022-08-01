<?php

namespace CommunityDS\Deputy\Api\Tests\Model;

use CommunityDS\Deputy\Api\Tests\Adapter\MockClient;
use CommunityDS\Deputy\Api\Tests\TestCase;

class CustomFieldTest extends TestCase
{
    public function testCreate()
    {
        $customField = $this->wrapper()->createCustomField();
        $customField->System = 'Timesheet';
        $customField->Name = 'New Custom Field';
        $customField->ApiName = 'casenotes';
        $customField->DeputyField = 'F03';
        $customField->Type = 2;

        $this->assertTrue($customField->isAttributeDirty('System'));
        $this->assertTrue($customField->isAttributeDirty('ApiName'));
        $this->assertTrue($customField->isAttributeDirty('DeputyField'));
        $this->assertTrue($customField->isAttributeDirty('Type'));
        $this->assertTrue($customField->save());
        $this->assertEquals(MockClient::CUSTOM_FIELD_NEW, $customField->id);
        $this->assertFalse($customField->isAttributeDirty('System'));
        $this->assertFalse($customField->isAttributeDirty('ApiName'));
        $this->assertFalse($customField->isAttributeDirty('DeputyField'));
        $this->assertFalse($customField->isAttributeDirty('Type'));

        $this->assertRequestLog(
            [
                ['get' => 'resource/CustomField/INFO'],
                ['put' => 'resource/CustomField'],
            ]
        );
    }

    public function testCreateFail()
    {
        $customField = $this->wrapper()->createCustomField();
        $customField->Name = 'Invalid Custom Field';
        $this->assertFalse($customField->save());
    }

    public function testUpdate()
    {
        $customField = $this->wrapper()->getCustomField(MockClient::CUSTOM_FIELD_FIRST);
        $customField->Name = 'Update Custom Field';

        $this->assertTrue($customField->isAttributeDirty('Name'));
        $this->assertTrue($customField->save());

        $this->assertFalse($customField->isAttributeDirty('Name'));

        $this->assertRequestLog(
            [
                ['get' => 'resource/CustomField/INFO'],
                ['get' => 'resource/CustomField/' . MockClient::CUSTOM_FIELD_FIRST],
                ['post' => 'resource/CustomField/' . MockClient::CUSTOM_FIELD_FIRST],
            ]
        );
    }

    public function testQueryAll()
    {
        $query = $this->wrapper()->findCustomFields();
        $expected = [MockClient::CUSTOM_FIELD_FIRST, MockClient::CUSTOM_FIELD_SECOND];
        $actual = [];
        foreach ($query->all() as $result) {
            $actual[] = $result->id;
        }
        $this->assertEquals($expected, $actual, 'Expecting all records');
    }

    public function testQuerySingle()
    {
        $query = $this->wrapper()->findCustomFields();
        $query->limit(1)->offset(1);
        $expected = [0 => MockClient::CUSTOM_FIELD_FIRST];
        $actual = [];
        $results = $query->all();
        foreach ($results as $result) {
            $actual[] = $result->id;
        }
        $this->assertEquals($expected, $actual, 'Expecting only 1 record');
    }
}
