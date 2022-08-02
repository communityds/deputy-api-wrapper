<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $employee
 * @property integer $employeeHistory
 * @property integer $employeeAgreement
 * @property DateTime $date
 * @property DateTime $startTime
 * @property DateTime $endTime
 * @property DateTime $mealbreak
 * @property string $mealbreakSlots
 * @property float $totalTime
 * @property float $totalTimeInv
 * @property float $cost
 * @property integer $roster
 * @property string $employeeComment
 * @property string $supervisorComment
 * @property integer $supervisor
 * @property boolean $disputed
 * @property boolean $timeApproved
 * @property integer $timeApprover
 * @property boolean $discarded
 * @property integer $validationFlag
 * @property integer $operationalUnit
 * @property boolean $isInProgress
 * @property boolean $isLeave
 * @property integer $leaveId
 * @property integer $leaveRule
 * @property boolean $invoiced
 * @property string $invoiceComment
 * @property boolean $payRuleApproved
 * @property boolean $exported
 * @property integer $stagingId
 * @property boolean $payStaged
 * @property integer $paycycleId
 * @property integer $file
 * @property integer $customFieldData
 * @property boolean $realTime
 * @property boolean $autoProcessed
 * @property boolean $autoRounded
 * @property boolean $autoTimeApproved
 * @property boolean $autoPayRuleApproved
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Employee $employeeObject
 * @property EmployeeAgreement $employeeAgreementObject
 * @property Roster $rosterObject
 * @property OperationalUnit $operationalUnitObject
 * @property Leave $leave
 * @property LeaveRules $leaveRuleObject
 * @property EmployeePaycycle $paycycle
 * @property CustomFieldData $customFieldDataObject
 */
class Timesheet extends Record
{
}
