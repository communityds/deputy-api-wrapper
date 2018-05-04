<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $title
 * @property integer $schedule
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Schedule $scheduleObject
 *
 * @property OperationalUnit $operationUnit
 */
class PublicHoliday extends Record
{

}
