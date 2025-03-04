<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property float $cost
 * @property DateTime $created
 * @property integer $creator
 * @property mixed $eventIds
 * @property DateTime $modified
 * @property boolean $overridden
 * @property string $overrideComment
 * @property integer $payRule
 * @property integer $timesheet
 * @property float $value
 *
 * @property PayRules $payRuleObject
 * @property Timesheet $timesheetObject
 */
class TimesheetPayReturn extends Record
{
}
