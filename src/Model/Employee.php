<?php

namespace CommunityDS\Deputy\Api\Model;

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
