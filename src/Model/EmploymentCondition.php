<?php

namespace CommunityDS\Deputy\Api\Model;

use DateInterval;
use DateTime;

/**
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $awardLevel
 * @property integer $employmentBasis
 * @property integer $employmentCategory
 * @property integer $employmentPeriod
 * @property integer $employmentStatus
 * @property integer $probationaryPeriod
 * @property float $workingDaysPerPeriod
 * @property DateInterval $usualStartTime
 * @property DateInterval $usualFinishTime
 * @property DateInterval $usualMealbreak
 * @property float $avgHoursPerDay
 * @property float $minHoursPerDay
 * @property float $minHoursForLeave
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 */
class EmploymentCondition extends Record
{

}
