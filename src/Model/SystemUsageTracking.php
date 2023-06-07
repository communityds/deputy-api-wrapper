<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $balanceId
 * @property integer $companyId
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $date
 * @property string $description
 * @property integer $empId
 * @property DateTime $modified
 * @property float $usage
 * @property integer $usageRecordId
 * @property integer $usageType
 *
 * @property SystemUsageBalance $balance
 * @property Company $company
 */
class SystemUsageTracking extends Record
{
}
