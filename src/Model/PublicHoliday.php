<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $modified
 * @property integer $schedule
 * @property string $title
 *
 * @property Schedule $scheduleObject
 *
 * @property OperationalUnit $operationUnit
 */
class PublicHoliday extends Record
{
}
