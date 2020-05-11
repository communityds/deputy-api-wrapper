<?php

namespace CommunityDS\Deputy\Api\Tests\Model;

use CommunityDS\Deputy\Api\Model\Company;
use CommunityDS\Deputy\Api\Tests\Adapter\MockClient;
use CommunityDS\Deputy\Api\Tests\TestCase;

class CompanyTest extends TestCase
{

    public function testCreate()
    {

        $company = $this->wrapper()->createCompany();
        $company->code = 'NEW';
        $company->companyName = 'New Company';
        $company->operationalUnitNames = ['First', 'Second'];
        $company->timeZone = 'Australia/Adelaide';

        $address = $company->createAddressObject();
        $address->street1 = '214 Greenhill Road';
        $address->city = 'Eastwood';
        $address->state = 'SA';
        $address->postcode = 5063;
        $address->country = MockClient::COUNTRY_AUSTRALIA;
        $address->notes = 'Company Notes';

        $this->assertEquals('Eastwood', $company->addressObject->city, 'Address object should be maintained');

        $this->assertTrue($company->save());

        $this->assertEquals(MockClient::COMPANY_NEW, $company->id);
        $this->assertEquals('NEW', $company->code);

        $this->assertEquals(MockClient::ADDRESS_COMPANY_NEW, $company->address);
        $this->assertNotNull($company->addressObject);
        $this->assertEquals('214 Greenhill Road, Eastwood SA 5063', $company->addressObject->street1);
        $this->assertEquals('Company Notes', $company->addressObject->notes);
    }

    public function testCreateFail()
    {
        $company = $this->wrapper()->createCompany();
        $company->code = 'FAIL';
        $this->assertFalse($company->save());
    }

    public function testUpdate()
    {
        $company = $this->wrapper()->getCompany(MockClient::COMPANY_FIRST);
        $this->expectException('CommunityDS\Deputy\Api\NotSupportedException');
        $company->save();
    }

    public function testCreateUpdateFail()
    {
        $company = $this->wrapper()->createCompany();
        $company->code = 'NEW';
        $company->companyName = 'New Company';
        $company->operationalUnitNames = ['First', 'Second'];
        $company->timeZone = 'Australia/Adelaide';
        $company->tradingName = 'FAKE';
        $this->expectException('CommunityDS\Deputy\Api\NotSupportedException');
        $company->save();
    }

    public function testAddOperationalUnit()
    {

        $company = $this->wrapper()->getCompany(MockClient::COMPANY_FIRST);
        $unit = $company->createOperationalUnitObject();
        $unit->operationalUnitName = 'New Unit';
        $this->assertEquals($company->id, $unit->company);
        $this->assertEquals($company->address, $unit->address);
        $this->assertEquals($company->contact, $unit->contact);
        $this->assertTrue($unit->showOnRoster);
        $this->assertTrue($unit->active);
        $unit->save();

        $this->assertEquals(MockClient::OP_UNIT_NEW, $unit->id);
    }

    public function testGetOperationalUnits()
    {

        $company = $this->wrapper()->getCompany(MockClient::COMPANY_FIRST);
        $units = $company->getOperationalUnitObjects();
        $expected = [MockClient::OP_UNIT_FIRST, MockClient::OP_UNIT_SECOND];
        $actual = [];
        foreach ($units as $unit) {
            $actual[] = $unit->id;
        }
        $this->assertEquals($expected, $actual);
    }
    
    public function testSetSettings()
    {
        $company = $this->wrapper()->getCompany(MockClient::COMPANY_FIRST);
    
        $resultSetSettings = $company->setSettings([
            Company::SETTING_WEEK_START => 1,
            Company::SETTING_CAN_BUMP_SHIFT_VIA_DESK => true,
        ]);
        $this->assertTrue($resultSetSettings, 'Failed to set company settings');
    
        $this->assertEquals(1, $company->getSetting(Company::SETTING_WEEK_START), "Error in getSetting('{Company::SETTING_WEEK_START}')");
        $this->assertEquals(true, $company->getSetting(Company::SETTING_CAN_BUMP_SHIFT_VIA_DESK), "Error in getSetting('{Company::SETTING_CAN_BUMP_SHIFT_VIA_DESK}')");
    }
    
    public function testGetSettings()
    {
        $company = $this->wrapper()->getCompany(MockClient::COMPANY_FIRST);
    
        $this->assertTrue(is_array($company->getSettings()), 'Settings are not an array');
    
        $this->assertEquals(1, $company->getSetting(Company::SETTING_WEEK_START), "Error in getSetting('{Company::SETTING_WEEK_START}')");
        $this->assertEquals(true, $company->getSetting(Company::SETTING_CAN_BUMP_SHIFT_VIA_DESK), "Error in getSetting('{Company::SETTING_CAN_BUMP_SHIFT_VIA_DESK}')");
    }
}
