<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $allDay
 * @property string $approvalComment
 * @property integer $approverPay
 * @property integer $approverTime
 * @property string $comment
 * @property integer $company
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $dateEnd
 * @property DateTime $dateStart
 * @property float $days
 * @property integer $employee
 * @property integer $employeeHistory
 * @property integer $end
 * @property string $externalId
 * @property integer $leaveRule
 * @property DateTime $modified
 * @property integer $start
 * @property integer $status
 * @property float $totalHours
 *
 * @property Company $companyObject
 * @property EmployeeHistory $employeeHistoryObject
 * @property Employee $employeeObject
 * @property LeaveRules $leaveRuleObject
 */
class Leave extends Record
{
    /**
     * Awaiting Approval status.
     */
    const STATUS_AWAITING_APPROVAL = 0;

    /**
     * Approved status.
     */
    const STATUS_APPROVED = 1;

    /**
     * Declined status.
     */
    const STATUS_DECLINED = 2;

    /**
     * Cancelled status.
     */
    const STATUS_CANCELLED = 3;

    /**
     * Date Approved status.
     */
    const STATUS_DATE_APPROVED = 4;

    /**
     * Pay Approved status.
     */
    const STATUS_PAY_APPROVED = 5;
}
