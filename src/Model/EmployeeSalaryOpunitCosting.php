<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $agreementHistory
 * @property float $cost
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $date
 * @property integer $dayTimestamp
 * @property integer $employee
 * @property integer $employeeAgreement
 * @property boolean $final
 * @property DateTime $modified
 * @property integer $opUnit
 *
 * @property EmployeeAgreement $employeeAgreementObject
 * @property Employee $employeeObject
 * @property OperationalUnit $opUnitObject
 */
class EmployeeSalaryOpunitCosting extends Record
{
}
