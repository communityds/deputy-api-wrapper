<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $employee
 * @property integer $type
 * @property DateTime $maxDateRecurringGenerated
 * @property DateTime $startTime
 * @property DateTime $endTime
 * @property DateTime $date
 * @property string $comment
 * @property integer $schedule
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Employee $employeeObject
 * @property Schedule $scheduleObject
 */
class EmployeeAvailability extends Record
{

}
