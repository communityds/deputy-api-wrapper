<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property float $costTotal
 * @property DateTime $created
 * @property integer $creator
 * @property integer $employeeAgreementHistoryId
 * @property integer $employeeAgreementId
 * @property integer $employeeId
 * @property integer $exportId
 * @property boolean $exported
 * @property DateTime $modified
 * @property boolean $paid
 * @property integer $paycycleRules
 * @property integer $paycycleRulesApproved
 * @property integer $periodId
 * @property boolean $recommendedLoadings
 * @property float $timeTotal
 * @property integer $timesheets
 * @property integer $timesheetsPayApproved
 * @property integer $timesheetsTimeApproved
 *
 * @property Employee $employee
 * @property EmployeeAgreement $employeeAgreement
 * @property CompanyPeriod $period
 */
class EmployeePaycycle extends Record
{
}
