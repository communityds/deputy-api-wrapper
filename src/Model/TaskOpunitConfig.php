<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $active
 * @property string $availableAfter
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $modified
 * @property integer $opUnitId
 * @property integer $schedule
 * @property integer $sortOrder
 * @property integer $taskGroupId
 * @property integer $taskSetupId
 * @property integer $type
 *
 * @property OperationalUnit $opUnit
 * @property Schedule $scheduleObject
 * @property TaskGroupSetup $taskGroup
 * @property TaskSetup $taskSetup
 */
class TaskOpunitConfig extends Record
{
}
