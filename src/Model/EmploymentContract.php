<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property integer $employmentBasis
 * @property integer $employmentCategory
 * @property integer $employmentStatus
 * @property integer $employmentCondition
 * @property integer $basePayRule
 * @property integer $stressProfile
 * @property DateTime $startDate
 * @property DateTime $endDate
 * @property integer $periodType
 * @property integer $file
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property EmploymentCondition $employmentConditionObject
 * @property PayRules $basePayRuleObject
 *
 * @property PayRules $payRules
 */
class EmploymentContract extends Record
{

}
