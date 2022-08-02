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
 * @property integer $emergencyAddress
 * @property DateTime $dateOfBirth
 * @property integer $gender
 * @property integer $photo
 * @property integer $jobAppId
 * @property boolean $active
 * @property DateTime $startDate
 * @property DateTime $terminationDate
 * @property string $position
 * @property integer $role
 * @property integer $employeeId
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 */
class EmployeeHistory extends Record
{
}
