<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $employee
 * @property integer $employeeHistory
 * @property DateTime $transactionDate
 * @property integer $type
 * @property integer $leaveRule
 * @property float $hours
 * @property string $comment
 * @property integer $fkId
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
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
