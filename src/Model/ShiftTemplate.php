<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $active
 * @property mixed $additionalConfig
 * @property boolean $autoCommitBreak
 * @property boolean $blockEarlyBreakEnd
 * @property boolean $blockEarlyBreakStart
 * @property integer $country
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $modified
 * @property string $name
 * @property integer $schedule
 * @property string $slots
 * @property float $totalTime
 *
 * @property Country $countryObject
 * @property Schedule $scheduleObject
 *
 * @property OperationalUnit $operationUnit
 */
class ShiftTemplate extends Record
{
}
