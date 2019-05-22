<?php

namespace CommunityDS\Deputy\Api\Tests\Model;

use CommunityDS\Deputy\Api\Tests\Adapter\MockClient;
use CommunityDS\Deputy\Api\Tests\TestCase;

class RecordTest extends TestCase
{

    /**
     * @return \CommunityDS\Deputy\Api\Model\Company
     */
    protected function getCompany()
    {
        $company = $this->resource('Company')->findOne(MockClient::COMPANY_FIRST);
        $this->assertNotNull($company);
        return $company;
    }

    public function testFields()
    {
        $company = $this->getCompany();
        $this->assertEquals('First Company', $company->CompanyName);
        $this->assertEquals('First Company', $company->comPanYnamE, 'Fields are case-insensitive');

        $this->assertRequestLog(
            [
                ['get' => 'resource/Company/INFO'],
                ['get' => 'resource/Company/' . MockClient::COMPANY_FIRST],
            ]
        );

        $this->assertTrue($company->hasAttribute('ComPANYname'));
        $this->assertFalse($company->hasAttribute('FakeAttributeHere'));
        $this->assertEquals('First Company', $company->getAttribute('CompanyName'));

        $company->comPanYnamE = 'New Name';
        $this->assertEquals('New Name', $company->companyName, 'Fields are case-insensitive');

        $company->setAttribute('comPanYnamE', 'New #2');
        $this->assertEquals('New #2', $company->companyName);

        $company->setAttributes(['companyName' => 'New #3', 'code' => 'NEW3']);
        $this->assertEquals('New #3', $company->companyName);
        $this->assertEquals('NEW3', $company->code);
    }

    public function testRelations()
    {
        $company = $this->getCompany();
        $this->assertFalse($company->isRelationPopulated('AddressObject'));
        $address = $company->addressObject;
        $this->assertTrue($company->isRelationPopulated('AddressObject'));
        $this->assertNotNull($address);
        $this->assertEquals($address->id, MockClient::ADDRESS_COMPANY);

        $expectedRequests = [
            ['get' => 'resource/Company/INFO'],
            ['get' => 'resource/Company/' . MockClient::COMPANY_FIRST],
            ['get' => 'resource/Address/INFO'],
            ['get' => 'resource/Company/' . MockClient::COMPANY_FIRST . '/AddressObject'],
        ];
        $this->assertRequestLog(
            $expectedRequests
        );

        $company->addressObject->street1 = 'New Street';
        $this->assertEquals('New Street', $company->addressObject->street1, 'Relations are persistent');

        $this->assertNotNull($company->ADdresSoBject, 'Relations are case-insensitive');

        $this->assertRequestLog(
            $expectedRequests
        );

        $address = $this->resource('Address')->findOne(MockClient::ADDRESS_COMPANY);
        $this->assertNotNull($address);
        $this->assertEquals(MockClient::ADDRESS_COMPANY, $address->id);

        $expectedRequests[] = ['get' => 'resource/Address/' . MockClient::ADDRESS_COMPANY];
        $this->assertRequestLog(
            $expectedRequests
        );

        $address = $this->resource('Address')->findOne(MockClient::ADDRESS_COMPANY);
        $this->assertNotNull($address);
        $this->assertEquals(MockClient::ADDRESS_COMPANY, $address->id);

        $expectedRequests[] = ['get' => 'resource/Address/' . MockClient::ADDRESS_COMPANY];
        $this->assertRequestLog(
            $expectedRequests
        );
    }

    public function testQuery()
    {

        $query = $this->resource('Address')->find();

        $expected = [MockClient::ADDRESS_COMPANY, MockClient::ADDRESS_FIRST];
        $actual = [];
        foreach ($query->all() as $result) {
            $actual[] = $result->id;
        }
        $this->assertEquals($expected, $actual);

        $query->limit(1)->offset(1);
        $expected = [MockClient::ADDRESS_FIRST];
        $actual = [];
        foreach ($query->all() as $result) {
            $actual[] = $result->id;
        }
        $this->assertEquals($expected, $actual);

        $query->limit(1)->offset(null);
        $expected = [MockClient::ADDRESS_COMPANY];
        $actual = [];
        foreach ($query->all() as $result) {
            $actual[] = $result->id;
        }
        $this->assertEquals($expected, $actual);
    }

    public function testQueryJoin()
    {

        $query = $this->resource('Company')->find()
            ->joinWith('addressObject')->joinWith('team');
        $company = $query->one();
        $this->assertNotNull($company);
        $this->assertEquals(MockClient::COMPANY_FIRST, $company->id);

        $expectedRequests = [
            ['get' => 'resource/Company/INFO'],
            ['post' => 'resource/Company/QUERY'],
        ];
        $this->assertRequestLog($expectedRequests);

        $this->assertFalse($company->isRelationPopulated('AddressObject'));
        $address = $company->addressObject;
        $this->assertTrue($company->isRelationPopulated('AddressObject'));
        $this->assertEquals(MockClient::ADDRESS_COMPANY, $address->id);

        $expectedRequests[] = ['get' => 'resource/Address/INFO'];
        $this->assertRequestLog($expectedRequests, 'Address should be already loaded');
    }

    public function testQueryConditions()
    {

        $query = $this->resource('Address')->find()
            ->andWhere(['>=', 'created', new \DateTime('2018-04-27T16:15:25', new \DateTimeZone('Australia/Adelaide'))]);
        $expected = [MockClient::ADDRESS_FIRST];
        $actual = [];
        foreach ($query->all() as $result) {
            $actual[] = $result->id;
        }
        $this->assertEquals($expected, $actual);

        $query = $this->resource('Address')->find()
            ->andWhere(['phone' => ['0812345678', '1234567890']]) // multi-value
            ->andWhere(['city' => 'eastwood']); // case-insensitive
        $expected = [MockClient::ADDRESS_COMPANY, MockClient::ADDRESS_FIRST];
        $actual = [];
        foreach ($query->all() as $result) {
            $actual[] = $result->id;
        }
        $this->assertEquals($expected, $actual);

        $query = $this->resource('Address')->find()
            ->andWhere(['phone' => ['1234567890']]) // multi-value
            ->andWhere(['city' => 'eastwood']); // case-insensitive
        $expected = [MockClient::ADDRESS_FIRST];
        $actual = [];
        foreach ($query->all() as $result) {
            $actual[] = $result->id;
        }
        $this->assertEquals($expected, $actual);
    }

    public function testQueryBatched()
    {

        $query = $this->resource('Address')->find();

        $expected = [MockClient::ADDRESS_COMPANY, MockClient::ADDRESS_FIRST];
        $actual = [];
        foreach ($query->all() as $result) {
            $actual[] = $result->id;
        }
        $this->assertEquals($expected, $actual);

        $this->assertRequestLog(
            [
                ['get' => 'resource/Address/INFO'],
                ['post' => 'resource/Address/QUERY'], // result count less than batch size
            ]
        );

        $this->clearRequestLog();
        $query->batchSize(1);

        $actual = [];
        foreach ($query->all() as $result) {
            $actual[] = $result->id;
        }
        $this->assertEquals($expected, $actual);

        $this->assertRequestLog(
            [
                ['post' => 'resource/Address/QUERY'], // first, result count equals batch size
                ['post' => 'resource/Address/QUERY'], // second, result count equals batch size
                ['post' => 'resource/Address/QUERY'], // none, result count less than batch size
            ]
        );
    }

    public function testQueryOrderBy()
    {

        $query = $this->resource('Address')->find();
        $expected = ['0812345678', '1234567890'];
        $actual = [];
        foreach ($query->all() as $result) {
            $actual[] = $result->phone;
        }
        $this->assertEquals($expected, $actual);

        $query = $this->resource('Address')->find()
            ->orderBy(
                [
                    'phone' => SORT_ASC,
                ]
            );
        $expected = ['0812345678', '1234567890'];
        $actual = [];
        foreach ($query->all() as $result) {
            $actual[] = $result->phone;
        }
        $this->assertEquals($expected, $actual);

        $query = $this->resource('Address')->find()
            ->orderBy(
                [
                    'phone' => SORT_DESC,
                ]
            );
        $expected = ['1234567890', '0812345678'];
        $actual = [];
        foreach ($query->all() as $result) {
            $actual[] = $result->phone;
        }
        $this->assertEquals($expected, $actual);
    }

    public function testDirty()
    {

        $address = $this->wrapper()->createAddress();
        $this->assertEquals([], $address->getDirtyAttributes());
        $this->assertFalse($address->isAttributeDirty('street1'));

        $expected = [
            'Street1' => 'Testing',
            'Country' => MockClient::COUNTRY_AUSTRALIA,
        ];
        $address->setAttributes($expected);
        $this->assertEquals($expected, $address->getDirtyAttributes());
        $this->assertNull($address->getOldAttribute('Street1'));
        $this->assertTrue($address->isAttributeDirty('street1'));

        unset($expected['Country']);
        $address->setOldAttributes($expected);
        $this->assertEquals($expected, $address->getDirtyAttributes());
        $this->assertEquals('Testing', $address->getOldAttribute('Street1'));

        $address = $this->wrapper()->createAddress();
        $address->street1 = 'Testing';
        $this->assertNull($address->getOldAttribute('street1'));
        $address->street1 = 'Testing';
        $this->assertNull($address->getOldAttribute('street1'), 'Null was original value');

        $expected = [
            'Street1' => 'Testing',
            'Country' => MockClient::COUNTRY_AUSTRALIA,
        ];
        $address = $this->wrapper()->createAddress();
        $this->assertFalse($address->isAttributeDirty('street1'));
        $address->setAttributes($expected);
        $address->setOldAttributes($expected);
        $this->assertTrue($address->isAttributeDirty('street1'));
        $this->assertEquals('Testing', $address->getOldAttribute('street1'));
        $address->street1 = 'New Testing';
        $this->assertEquals('Testing', $address->getOldAttribute('street1'));
        $this->assertTrue($address->isAttributeDirty('street1'));
    }

    public function testCreate()
    {

        $address = $this->wrapper()->createAddress();
        $this->assertEquals(null, $address->street1);
        $this->assertEquals(null, $address->country);

        $address->country = MockClient::COUNTRY_AUSTRALIA;
        $address->setAttributes(
            [
                'street1' => 'New Address',
            ]
        );
        $this->assertEquals('New Address', $address->street1);
        $this->assertTrue($address->isAttributeDirty('street1'));
        $this->assertEquals(MockClient::COUNTRY_AUSTRALIA, $address->country);

        $this->assertTrue($address->save());

        $this->assertEquals(MockClient::ADDRESS_NEW, $address->id);

        $this->assertFalse($address->isAttributeDirty('street1'));
    }

    public function testCreateFail()
    {

        $address = $this->wrapper()->createAddress();
        $address->country = MockClient::COUNTRY_INVALID;
        $this->assertTrue($address->isAttributeDirty('country'));
        $this->assertFalse($address->save());
        $this->assertTrue($address->isAttributeDirty('country'));
    }

    public function testUpdate()
    {

        $address = $this->wrapper()->getAddress(MockClient::ADDRESS_FIRST);
        $this->assertEquals('2 Greenhill Road', $address->street1);

        $address->street1 = '2 George Street';
        $this->assertTrue($address->isAttributeDirty('street1'));
        $this->assertTrue($address->save());

        $this->assertEquals('2 George Street', $address->street1);
        $this->assertFalse($address->isAttributeDirty('street1'));
    }

    public function testUpdateFail()
    {

        $address = $this->wrapper()->getAddress(MockClient::ADDRESS_FIRST);
        $address->country = MockClient::COUNTRY_INVALID;
        $this->assertTrue($address->isAttributeDirty('country'));
        $this->assertFalse($address->save());
        $this->assertTrue($address->isAttributeDirty('country'));
    }

    public function testDelete()
    {

        $address = $this->wrapper()->getAddress(MockClient::ADDRESS_FIRST);
        $this->assertTrue($address->delete());
    }

    public function testDeleteFail()
    {

        $address = $this->wrapper()->getAddress(MockClient::ADDRESS_COMPANY);
        $this->assertFalse($address->delete());
    }

    public function testUpdateRelated()
    {

        $company = $this->wrapper()->getCompany(MockClient::COMPANY_FIRST);
        $address = $company->addressObject;
        $address->street1 = 'New Street Address';

        $requestLog = [
            ['get' => 'resource/Company/INFO'],
            ['get' => 'resource/Company/' . MockClient::COMPANY_FIRST],
            ['get' => 'resource/Address/INFO'],
            ['get' => 'resource/Company/' . MockClient::COMPANY_FIRST . '/AddressObject'],
        ];
        $this->assertRequestLog($requestLog);

        $this->assertTrue($address->isAttributeDirty('street1'));
        $address->save();
        $this->assertEquals('New Street Address', $address->street1);
        $this->assertFalse($address->isAttributeDirty('street1'));

        $requestLog[] = ['post' => 'resource/Company/' . MockClient::COMPANY_FIRST . '/AddressObject'];
        $this->assertRequestLog($requestLog);
    }
}
