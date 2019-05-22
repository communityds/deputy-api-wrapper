<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $timesheet
 * @property integer $payRule
 * @property boolean $overridden
 * @property float $value
 * @property float $cost
 * @property string $overrideComment
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Timesheet $timesheetObject
 * @property PayRules $payRuleObject
 */
class TimesheetPayReturn extends Record
{

}
