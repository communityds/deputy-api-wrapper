<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $employeeId
 * @property integer $payPoint
 * @property integer $empType
 * @property string $companyName
 * @property boolean $active
 * @property DateTime $startDate
 * @property integer $contract
 * @property integer $salaryPayRule
 * @property integer $contractFile
 * @property string $payrollId
 * @property integer $payPeriod
 * @property integer $historyId
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Employee $employee
 * @property Company $payPointObject
 * @property EmploymentContract $contractObject
 * @property PayRules $salaryPayRuleObject
 * @property PayPeriod $payPeriodObject
 *
 * @property OperationalUnit $employeeSalaryOpunits
 */
class EmployeeAgreement extends Record
{

}
