<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $agreement1
 * @property integer $agreement2
 * @property integer $agreement3
 * @property integer $company
 * @property DateTime $created
 * @property integer $creator
 * @property integer $employeeId
 * @property DateTime $modified
 * @property integer $sortOrder
 *
 * @property EmployeeAgreement $agreement1Object
 * @property EmployeeAgreement $agreement2Object
 * @property EmployeeAgreement $agreement3Object
 * @property Company $companyObject
 * @property Employee $employee
 */
class EmployeeWorkplace extends Record
{
}
