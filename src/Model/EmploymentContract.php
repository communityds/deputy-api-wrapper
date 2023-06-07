<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property string $award
 * @property DateTime $awardStartDate
 * @property integer $basePayRule
 * @property string $code
 * @property integer $countryId
 * @property DateTime $created
 * @property integer $creator
 * @property string $description
 * @property integer $employmentBasis
 * @property integer $employmentCategory
 * @property integer $employmentCondition
 * @property integer $employmentStatus
 * @property string $employmentSubType
 * @property DateTime $endDate
 * @property integer $file
 * @property DateTime $modified
 * @property string $name
 * @property integer $periodType
 * @property string $ppId
 * @property DateTime $startDate
 * @property integer $stressProfile
 * @property boolean $strictLeaveApproval
 *
 * @property PayRules $basePayRuleObject
 * @property Country $country
 * @property EmploymentCondition $employmentConditionObject
 *
 * @property CustomField $customField
 * @property PayRules $payRules
 */
class EmploymentContract extends Record
{
}
