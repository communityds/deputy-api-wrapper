<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $employee
 * @property integer $employeeHistory
 * @property integer $company
 * @property integer $leaveRule
 * @property integer $start
 * @property DateTime $dateStart
 * @property integer $end
 * @property DateTime $dateEnd
 * @property float $days
 * @property integer $approverTime
 * @property integer $approverPay
 * @property string $comment
 * @property integer $status
 * @property string $approvalComment
 * @property float $totalHours
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Employee $employeeObject
 * @property EmployeeHistory $employeeHistoryObject
 * @property Company $companyObject
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
