<?php

namespace CommunityDS\Deputy\Api\Tests;

use CommunityDS\Deputy\Api\Tests\Adapter\MockClient;

class WrapperTestCase extends TestCase
{
    public function testGetSingular()
    {
        $company = $this->wrapper()->getCompany(MockClient::COMPANY_FIRST);
        $this->assertNotNull($company);
        $this->assertEquals(MockClient::COMPANY_FIRST, $company->id);
    }

    public function testGetMultiple()
    {
        $expected = [MockClient::COMPANY_FIRST];
        $actual = [];
        $companies = $this->wrapper()->getCompanies();
        foreach ($companies as $company) {
            $actual[] = $company->id;
        }
        $this->assertEquals($expected, $actual);
    }

    public function testFind()
    {
        $expected = [MockClient::COMPANY_FIRST];
        $actual = [];
        $companies = $this->wrapper()->findCompanies();
        $this->assertNotNull($companies);
        $this->assertInstanceOf('CommunityDS\Deputy\Api\Query', $companies);
        foreach ($companies->all() as $company) {
            $actual[] = $company->id;
        }
        $this->assertEquals($expected, $actual);
    }

    public function testCreate()
    {
        $company = $this->wrapper()->createCompany();
        $this->assertEquals(null, $company->id);
        $this->assertEquals(null, $company->companyName);
        $company = $this->wrapper()->createCompany(['Id' => 123, 'CompanyName' => 'New']);
        $this->assertEquals(123, $company->id);
        $this->assertEquals('New', $company->companyName);
    }

    public function testDelete()
    {
        $this->assertTrue($this->wrapper()->deleteCompany(MockClient::COMPANY_FIRST));
        $this->assertFalse($this->wrapper()->deleteCompany(MockClient::COMPANY_NEW));
    }
}
