<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property DateTime $created
 * @property integer $creator
 * @property string $customRules
 * @property float $gapHoursBetweenShifts
 * @property float $maxDaysPerPeriod
 * @property float $maxHoursPerDay
 * @property float $maxHoursPerPeriod
 * @property float $maxHoursPerShift
 * @property DateTime $modified
 * @property string $name
 */
class StressProfile extends Record
{
}
