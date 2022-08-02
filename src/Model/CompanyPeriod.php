<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $start
 * @property DateTime $dateStart
 * @property integer $end
 * @property DateTime $dateEnd
 * @property integer $company
 * @property integer $payPeriod
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Company $companyObject
 * @property PayPeriod $payPeriodObject
 */
class CompanyPeriod extends Record
{
}
