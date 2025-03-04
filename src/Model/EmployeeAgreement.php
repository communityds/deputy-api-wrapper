<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $active
 * @property float $baseRate
 * @property string $companyName
 * @property mixed $config
 * @property integer $contract
 * @property integer $contractFile
 * @property DateTime $created
 * @property integer $creator
 * @property integer $empType
 * @property integer $employeeId
 * @property DateTime $endDate
 * @property integer $historyId
 * @property integer $levelId
 * @property DateTime $modified
 * @property integer $payCalendarId
 * @property integer $payPeriod
 * @property integer $payPoint
 * @property string $payrollId
 * @property integer $salaryPayRule
 * @property DateTime $startDate
 *
 * @property EmploymentContract $contractObject
 * @property Employee $employee
 * @property PayPeriod $payPeriodObject
 * @property Company $payPointObject
 * @property PayRules $salaryPayRuleObject
 *
 * @property OperationalUnit $employeeSalaryOpunits
 */
class EmployeeAgreement extends Record
{
}
