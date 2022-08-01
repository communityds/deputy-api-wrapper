<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $taskSetupId
 * @property integer $taskGroupId
 * @property boolean $active
 * @property integer $sortOrder
 * @property integer $opUnitId
 * @property integer $schedule
 * @property integer $type
 * @property string $availableAfter
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property TaskSetup $taskSetup
 * @property TaskGroupSetup $taskGroup
 * @property OperationalUnit $opUnit
 * @property Schedule $scheduleObject
 */
class TaskOpunitConfig extends Record
{
}
