<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\NotSupportedException;
use DateTime;

/**
 * @property boolean $active
 * @property integer $address
 * @property string $businessNumber
 * @property string $code
 * @property string $companyName
 * @property string $companyNumber
 * @property integer $contact
 * @property DateTime $created
 * @property integer $creator
 * @property boolean $isPayrollEntity
 * @property boolean $isWorkplace
 * @property DateTime $modified
 * @property integer $parentCompany
 * @property string $payrollExportCode
 * @property integer $portfolio
 * @property string $tradingName
 *
 * @property Address $addressObject
 * @property Contact $contactObject
 * @property Company $parentCompanyObject
 *
 * @property Memo $company
 * @property Team $team
 *
 * @property Employee[] $employeeObjects
 *
 * @property string $displayName
 * @property OperationalUnit[] $operationalUnitObjects
 *
 * @property-write float $latitude Only when creating a company
 * @property-write float $longitude Only when creating a company
 * @property-write string[] $operationalUnitNames Only when creating a company
 * @property-write string $timeZone Only when creating a company
 */
class Company extends Record implements CompanySettingsInterface
{
    use CompanySettingsTrait;

    /**
     * Names of operational units to create for new companies.
     *
     * @var string[]
     */
    private $_operationalUnitNames;

    /**
     * Name of timezone to assign to new company.
     *
     * @var string
     */
    private $_timeZone;

    /**
     * Latitude of company address.
     *
     * @var float
     */
    private $_latitude;

    /**
     * Longitude of company address.
     *
     * @var float
     */
    private $_longitude;

    /**
     * Returns the company name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->companyName;
    }

    /**
     * Returns the employees for this company.
     *
     * @return Employee[]
     */
    public function getEmployeeObjects()
    {
        return $this->getWrapper()->findEmployees()
            ->andWhere(['company' => $this->id])
            ->all();
    }

    /**
     * Returns the operational units for this company.
     *
     * @return OperationalUnit[]
     */
    public function getOperationalUnitObjects()
    {
        return $this->getWrapper()->findOperationalUnit()
            ->andWhere(['company' => $this->id])
            ->all();
    }

    /**
     * Creates a new operation unit instance for this company,
     * that inherits the company address and contact.
     *
     * @return OperationalUnit
     */
    public function createOperationalUnitObject()
    {
        $unit = $this->getWrapper()->createOperationalUnit();
        $unit->showOnRoster = true;
        $unit->address = $this->address;
        $unit->company = $this->id;
        $unit->contact = $this->contact;
        $unit->active = true;
        $unit->payrollExportName = '';
        return $unit;
    }

    /**
     * Creates a new address object and populates the `addressObject`
     * relation with the instance.
     *
     * @return Address
     */
    public function createAddressObject()
    {
        $address = $this->getWrapper()->createAddress();
        $this->populateRelation('addressObject', $address);
        return $address;
    }

    /**
     * Sets the names of operational units that should be created when this
     * company is created.
     *
     * @param string[] $names Operational unit names
     *
     * @return void
     *
     * @throws NotSupportedException When setting name on an existing company
     */
    public function setOperationalUnitNames($names)
    {
        if ($this->getPrimaryKey() != null) {
            throw new NotSupportedException('Operational Unit names can only be set on new companies');
        }
        $this->_operationalUnitNames = $names;
    }

    /**
     * Sets the time zone for this company when it is created.
     *
     * @param string $timeZone Time zone name
     *
     * @return void
     *
     * @throws NotSupportedException When setting time zone on an existing company
     */
    public function setTimeZone($timeZone)
    {
        if ($this->getPrimaryKey() != null) {
            throw new NotSupportedException('Time Zone can only be set on new companies');
        }
        $this->_timeZone = $timeZone;
    }

    /**
     * Sets the latitude of the company when it is created.
     *
     * @param float $latitude Latitude value
     *
     * @return void
     */
    public function setLatitude($latitude)
    {
        $this->_latitude = $latitude;
    }

    /**
     * Sets the longitude of the company when it is created.
     *
     * @param float $longitude Longitude value
     *
     * @return void
     */
    public function setLongitude($longitude)
    {
        $this->_longitude = $longitude;
    }

    public function clearData()
    {
        parent::clearData();
        $this->_longitude = null;
        $this->_latitude = null;
        $this->_timeZone = null;
        $this->_operationalUnitNames = null;
    }

    /**
     * Creates new Company resources via the my/setup/addNewWorkplace endpoint,
     * and then performs an `update()` to add any missing information.
     *
     * @param string[] $attributeNames Names of attributes to store; or null to use
     *                                 only populated attributes.
     *
     * @return boolean
     */
    public function insert($attributeNames = null)
    {

        $insertPayload = $this->insertPayload($attributeNames);

        // Create payload for my/setup/addNewWorkplace by extracting
        // the expected values.

        $payload = [
            'strWorkplaceTimezone' => 'UTC', // no default timezone is known
            'strAddress' => '.',    // force addresses to always be created
        ];
        if ($this->_operationalUnitNames) {
            $payload['arrAreaNames'] = $this->_operationalUnitNames;
        }
        if ($this->_timeZone) {
            $payload['strWorkplaceTimezone'] = $this->_timeZone;
        }
        if ($this->_latitude !== null) {
            $payload['strLat'] = (string) $this->_latitude;
        }
        if ($this->_longitude !== null) {
            $payload['strLon'] = (string) $this->_longitude;
        }

        // Derive address information from the address object
        $address = $this->addressObject;
        if ($address) {
            $displayName = $address->getDisplayName();
            if ($displayName) {
                $payload['strAddress'] = $displayName;
            }
            if ($address->country) {
                $payload['intCountry'] = $address->country;
            }
            if ($address->notes) {
                $payload['strAddressNote'] = $address->notes;
            }
        }

        foreach ($insertPayload as $name => $value) {
            switch ($name) {
                case 'Code':
                    $payload['strWorkplaceCode'] = $value;
                    unset($insertPayload[$name]);
                    break;
                case 'CompanyName':
                    $payload['strWorkplaceName'] = $value;
                    unset($insertPayload[$name]);
                    break;
            }
        }

        $response = $this->getWrapper()->client->post(
            'my/setup/addNewWorkplace',
            $payload
        );
        if ($response === false) {
            $this->setErrorsFromResponse($this->getWrapper()->client->getLastError());
            return false;
        }

        static::populateRecord($this, $response);

        // Determine additional fields that the my/setup/addNewWorkplace endpoint
        // does not support and if any are found then change their value
        // within the model and force an update.

        $updateRecord = false;
        foreach ($insertPayload as $key => $value) {
            if (array_key_exists($key, $response)) {
                if ($response[$key] != $value) {
                    $this->{$key} = $value;
                    $updateRecord = true;
                }
            } elseif (!array_key_exists($key, $response)) {
                $this->{$key} = $value;
                $updateRecord = true;
            }
        }

        if ($updateRecord) {
            return $this->save();
        }

        return true;
    }

    /**
     * Companies can not be updated via the API.
     *
     * @param string[] $attributeNames Names of attributes to store; or null to use
     *                                 only populated attributes.
     *
     * @return void
     *
     * @throws NotSupportedException Always, as companies can not be updated via the API
     */
    public function update($attributeNames = null)
    {
        throw new NotSupportedException('Companies can not be updated via the API at this time');
    }
}
