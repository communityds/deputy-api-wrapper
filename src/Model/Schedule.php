<?php

namespace CommunityDS\Deputy\Api\Model;

use DateInterval;
use DateTime;

/**
 * @property integer $id
 * @property string $name
 * @property DateTime $startDate
 * @property DateInterval $startTime
 * @property DateInterval $endTime
 * @property integer $repeatType
 * @property integer $repeatEvery
 * @property string $weeklyOnDays
 * @property string $monthlyOnDates
 * @property string $monthlyOnDays
 * @property DateTime $endDate
 * @property string $exception
 * @property boolean $saved
 * @property string $orm
 * @property boolean $template
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 */
class Schedule extends Record
{
}
