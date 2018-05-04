<?php

namespace CommunityDS\Deputy\Api\Model;

use DateInterval;
use DateTime;

/**
 * @property integer $id
 * @property integer $leaveId
 * @property integer $leaveRule
 * @property integer $employeeAgreement
 * @property DateTime $date
 * @property DateInterval $startTime
 * @property DateInterval $endTime
 * @property string $hours
 * @property string $comment
 * @property integer $timesheetId
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Leave $leave
 * @property LeaveRules $leaveRuleObject
 * @property EmployeeAgreement $employeeAgreementObject
 * @property Timesheet $timesheet
 */
class LeavePayLine extends Record
{

}
