<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property DateTime $date
 * @property integer $empId
 * @property integer $companyId
 * @property integer $balanceId
 * @property integer $usageType
 * @property integer $usageRecordId
 * @property float $usage
 * @property string $description
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Company $company
 * @property SystemUsageBalance $balance
 */
class SystemUsageTracking extends Record
{

}
