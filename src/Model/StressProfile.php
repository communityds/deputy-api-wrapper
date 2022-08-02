<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $name
 * @property float $maxHoursPerShift
 * @property float $maxHoursPerPeriod
 * @property float $maxDaysPerPeriod
 * @property float $maxHoursPerDay
 * @property float $gapHoursBetweenShifts
 * @property string $customRules
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 */
class StressProfile extends Record
{
}
