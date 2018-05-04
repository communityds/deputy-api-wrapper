<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $employeeId
 * @property integer $employeeAgreementId
 * @property integer $periodId
 * @property boolean $recommendedLoadings
 * @property integer $timesheets
 * @property integer $timesheetsTimeApproved
 * @property integer $timesheetsPayApproved
 * @property integer $paycycleRules
 * @property integer $paycycleRulesApproved
 * @property boolean $exported
 * @property integer $exportId
 * @property boolean $paid
 * @property float $timeTotal
 * @property float $costTotal
 * @property integer $employeeAgreementHistoryId
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Employee $employee
 * @property EmployeeAgreement $employeeAgreement
 * @property CompanyPeriod $period
 */
class EmployeePaycycle extends Record
{

}
