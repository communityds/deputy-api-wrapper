<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\NotSupportedException;
use DateTime;

/**
 * @property boolean $active
 * @property boolean $allowAppraisal
 * @property integer $company
 * @property integer $contact
 * @property DateTime $created
 * @property integer $creator
 * @property integer $customFieldData
 * @property string $customPronouns
 * @property DateTime $dateOfBirth
 * @property string $displayName
 * @property integer $emergencyAddress
 * @property string $employmentEndComment
 * @property DateTime $employmentEndDate
 * @property mixed $employmentEndReason
 * @property mixed $employmentEndSentiment
 * @property string $externalLinkId
 * @property string $firstName
 * @property integer $gender
 * @property boolean $higherDuty
 * @property integer $historyId
 * @property integer $jobAppId
 * @property string $lastName
 * @property integer $mainAddress
 * @property DateTime $modified
 * @property string $onboardingId
 * @property string $otherName
 * @property integer $photo
 * @property string $position
 * @property integer $postalAddress
 * @property integer $pronouns
 * @property integer $role
 * @property string $salutation
 * @property DateTime $startDate
 * @property integer $stressProfile
 * @property DateTime $terminationDate
 * @property integer $userId
 *
 * @property Company $companyObject
 * @property Contact $contactObject
 * @property CustomFieldData $customFieldDataObject
 * @property Address $emergencyAddressObject
 * @property Address $mainAddressObject
 * @property Address $postalAddressObject
 * @property EmployeeRole $roleObject
 * @property StressProfile $stressProfileObject
 *
 * @property Team $employee
 * @property OperationalUnit $managementEmployeeOperationalUnit
 * @property PayRules $payRules
 * @property OperationalUnit $rosterEmployeeOperationalUnit
 *
 * @property TrainingRecord[] $trainingRecordObjects
 *
 * @property string $email Only when creating an employee
 * @property string $mobilePhone Only when creating an employee
 * @property string $street Only when creating an employee
 * @property string $state Only when creating an employee
 * @property string $countryCode Only when creating an employee
 * @property string $city Only when creating an employee
 * @property string $postCode Only when creating an employee
 */
class Employee extends Record
{
    /**
     * Value for Female gender.
     */
    const GENDER_FEMALE = 2;

    /**
     * Value for Male gender.
     */
    const GENDER_MALE = 1;

    /**
     * Value for Non-Binary gender.
     */
    const GENDER_NON_BINARY = 3;

    /**
     * Value for Prefer not to say gender.
     */
    const GENDER_UNKNOWN = 0;

    /**
     * Values to send when creating an employee.
     *
     * @var array
     */
    private $_create = [];

    /**
     * Sets the email address when creating a new employee.
     *
     * @param string $email
     *
     * @return void
     *
     * @throws NotSupportedException When setting email on an existing employee
     */
    public function setEmail($email)
    {
        if ($this->getPrimaryKey() != null) {
            throw new NotSupportedException('Email can only be set on new employees');
        }
        $this->_create['strEmail'] = $email;
    }

    /**
     * Sets the mobile phone number when creating a new employee.
     *
     * @param string $number
     *
     * @return void
     *
     * @throws NotSupportedException When setting number on an existing employee
     */
    public function setMobilePhone($number)
    {
        if ($this->getPrimaryKey() != null) {
            throw new NotSupportedException('Mobile Phone can only be set on new employees');
        }
        $this->_create['strMobilePhone'] = $number;
    }

    /**
     * Sets the street address when creating a new employee.
     *
     * @param string $address
     *
     * @return void
     *
     * @throws NotSupportedException When setting address on an existing employee
     */
    public function setStreet($address)
    {
        if ($this->getPrimaryKey() != null) {
            throw new NotSupportedException('Street can only be set on new employees');
        }
        $this->_create['strStreet'] = $address;
    }

    /**
     * Sets the state when creating a new employee.
     *
     * @param string $state
     *
     * @return void
     *
     * @throws NotSupportedException When setting state on an existing employee
     */
    public function setState($state)
    {
        if ($this->getPrimaryKey() != null) {
            throw new NotSupportedException('State can only be set on new employees');
        }
        $this->_create['strState'] = $state;
    }

    /**
     * Sets the country code when creating a new employee.
     *
     * @param string $code
     *
     * @return void
     *
     * @throws NotSupportedException When setting country code on an existing employee
     */
    public function setCountryCode($code)
    {
        if ($this->getPrimaryKey() != null) {
            throw new NotSupportedException('Country Code can only be set on new employees');
        }
        $this->_create['strCountryCode'] = $code;
    }

    /**
     * Sets the city when creating a new employee.
     *
     * @param string $city
     *
     * @return void
     *
     * @throws NotSupportedException When setting city on an existing employee
     */
    public function setCity($city)
    {
        if ($this->getPrimaryKey() != null) {
            throw new NotSupportedException('City can only be set on new employees');
        }
        $this->_create['strCity'] = $city;
    }

    /**
     * Sets the postal code when creating a new employee.
     *
     * @param string $postCode
     *
     * @return void
     *
     * @throws NotSupportedException When setting the value on an existing employee
     */
    public function setPostCode($postCode)
    {
        if ($this->getPrimaryKey() != null) {
            throw new NotSupportedException('Post Code can only be set on new employees');
        }
        $this->_create['strPostCode'] = $postCode;
    }

    /**
     * Creates new Company resources via the supervise/employee endpoint,
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

        // Create payload for supervise/employee by extracting
        // the expected values.

        $payload = array_merge(
            [
                'strFirstName' => '',
                'strLastName' => '',
                'intCompanyId' => 0,
            ],
            $this->_create
        );

        foreach ($insertPayload as $name => $value) {
            switch ($name) {
                case 'Company':
                    $payload['intCompanyId'] = $value;
                    unset($insertPayload[$name]);
                    break;
                case 'FirstName':
                    $payload['strFirstName'] = $value;
                    unset($insertPayload[$name]);
                    break;
                case 'LastName':
                    $payload['strLastName'] = $value;
                    unset($insertPayload[$name]);
                    break;
            }
        }

        $response = $this->getWrapper()->client->post(
            'supervise/employee',
            $payload
        );
        if ($response === false) {
            $this->setErrorsFromResponse($this->getWrapper()->client->getLastError());
            return false;
        }

        static::populateRecord($this, $response);

        // Determine additional fields that the supervise/employee endpoint
        // does not support and if any are found then change their value
        // within the model and force an update.

        $updateRecord = false;
        foreach ($insertPayload as $key => $value) {
            if (array_key_exists($key, $response)) {
                if ($response[$key] != $value) {
                    $this->{$key} = $value;
                    $updateRecord = true;
                }
            } else {
                $this->{$key} = $value;
                $updateRecord = true;
            }
        }

        if ($updateRecord) {
            return $this->save();
        }

        return true;
    }

    public function clearData()
    {
        parent::clearData();
        $this->_create = [];
    }

    /**
     * Creates a new roster for this employee.
     *
     * @return Roster
     */
    public function createRoster()
    {
        $roster = $this->getWrapper()->createRoster();
        $roster->employee = $this->id;
        return $roster;
    }

    /**
     * Returns the training records for this employee.
     *
     * @return TrainingRecord[]
     */
    public function getTrainingRecordObjects()
    {
        return $this->getWrapper()->findTrainingRecords()
            ->andWhere(['employee' => $this->id])
            ->joinWith('moduleObject')
            ->all();
    }
}
