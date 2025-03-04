<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $company
 * @property integer $companyPeriod
 * @property DateTime $created
 * @property integer $creator
 * @property mixed $exportData
 * @property string $label
 * @property DateTime $modified
 *
 * @property Company $companyObject
 * @property CompanyPeriod $companyPeriodObject
 */
class TimesheetExport extends Record
{
}
