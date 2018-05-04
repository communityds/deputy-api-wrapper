<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $paycycleId
 * @property integer $payRule
 * @property boolean $approved
 * @property boolean $overridden
 * @property float $value
 * @property float $cost
 * @property string $overrideComment
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property EmployeePaycycle $paycycle
 * @property PayRules $payRuleObject
 */
class EmployeePaycycleReturn extends Record
{

}
