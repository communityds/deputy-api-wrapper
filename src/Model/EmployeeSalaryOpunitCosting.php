<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $employee
 * @property integer $employeeAgreement
 * @property integer $agreementHistory
 * @property integer $dayTimestamp
 * @property DateTime $date
 * @property integer $opUnit
 * @property float $cost
 * @property boolean $final
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Employee $employeeObject
 * @property EmployeeAgreement $employeeAgreementObject
 * @property OperationalUnit $opUnitObject
 */
class EmployeeSalaryOpunitCosting extends Record
{
}
