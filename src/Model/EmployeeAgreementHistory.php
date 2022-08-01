<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $agreementId
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
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 */
class EmployeeAgreementHistory extends Record
{
}
