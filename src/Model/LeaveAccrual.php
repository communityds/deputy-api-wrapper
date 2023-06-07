<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property DateTime $balanceCalculatedAt
 * @property string $comment
 * @property DateTime $created
 * @property integer $creator
 * @property float $days
 * @property integer $employee
 * @property integer $employeeHistory
 * @property integer $fkId
 * @property float $hours
 * @property integer $leaveRule
 * @property DateTime $modified
 * @property DateTime $transactionDate
 * @property integer $type
 *
 * @property Employee $employeeObject
 * @property LeaveRules $leaveRuleObject
 */
class LeaveAccrual extends Record
{
    /**
     * Accrual Entry type.
     */
    const ACCRUAL_ENTRY = 1;

    /**
     * Leave Entry type.
     */
    const LEAVE_ENTRY = 2;
}
