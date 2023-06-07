<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property string $comment
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $date
 * @property integer $employee
 * @property DateTime $endTime
 * @property DateTime $maxDateRecurringGenerated
 * @property DateTime $modified
 * @property integer $schedule
 * @property DateTime $startTime
 * @property integer $type
 *
 * @property Employee $employeeObject
 * @property Schedule $scheduleObject
 */
class EmployeeAvailability extends Record
{
}
