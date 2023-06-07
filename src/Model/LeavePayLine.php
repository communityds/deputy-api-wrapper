<?php

namespace CommunityDS\Deputy\Api\Model;

use DateInterval;
use DateTime;

/**
 * @property string $comment
 * @property float $cost
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $date
 * @property integer $employeeAgreement
 * @property DateTime $endTime
 * @property string $hours
 * @property integer $leaveId
 * @property integer $leaveRule
 * @property DateTime $modified
 * @property boolean $recalculateWithReferencePeriod
 * @property DateTime $startTime
 * @property integer $timesheetId
 *
 * @property EmployeeAgreement $employeeAgreementObject
 * @property Leave $leave
 * @property LeaveRules $leaveRuleObject
 * @property Timesheet $timesheet
 */
class LeavePayLine extends Record
{
}
