<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $company
 * @property string $firstName
 * @property string $lastName
 * @property string $displayName
 * @property string $otherName
 * @property string $salutation
 * @property integer $mainAddress
 * @property integer $postalAddress
 * @property integer $contact
 * @property integer $emergencyAddress
 * @property DateTime $dateOfBirth
 * @property integer $gender
 * @property integer $photo
 * @property integer $userId
 * @property integer $jobAppId
 * @property boolean $active
 * @property DateTime $startDate
 * @property DateTime $terminationDate
 * @property integer $stressProfile
 * @property string $position
 * @property boolean $higherDuty
 * @property integer $role
 * @property boolean $allowAppraisal
 * @property integer $historyId
 * @property integer $customFieldData
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Company $companyObject
 * @property Address $mainAddressObject
 * @property Address $postalAddressObject
 * @property Contact $contactObject
 * @property Address $emergencyAddressObject
 * @property StressProfile $stressProfileObject
 * @property EmployeeRole $roleObject
 * @property CustomFieldData $customFieldDataObject
 *
 * @property PayRules $payRules
 * @property OperationalUnit $managementEmployeeOperationalUnit
 * @property OperationalUnit $rosterEmployeeOperationalUnit
 * @property Team $employee
 *
 * @property TrainingRecord[] $trainingRecordObjects
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
