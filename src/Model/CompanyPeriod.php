<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $company
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $dateEnd
 * @property DateTime $dateStart
 * @property integer $end
 * @property DateTime $modified
 * @property integer $payPeriod
 * @property integer $start
 *
 * @property Company $companyObject
 * @property PayPeriod $payPeriodObject
 */
class CompanyPeriod extends Record
{
}
