<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $approved
 * @property float $cost
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $modified
 * @property boolean $overridden
 * @property string $overrideComment
 * @property integer $payRule
 * @property integer $paycycleId
 * @property float $value
 *
 * @property PayRules $payRuleObject
 * @property EmployeePaycycle $paycycle
 */
class EmployeePaycycleReturn extends Record
{
}
