<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $active
 * @property string $availableAfter
 * @property string $colour
 * @property DateTime $created
 * @property integer $creator
 * @property string $default
 * @property integer $duration
 * @property integer $groupId
 * @property mixed $helptext
 * @property DateTime $modified
 * @property mixed $onNo
 * @property mixed $onStart
 * @property mixed $onSubmit
 * @property mixed $onYes
 * @property integer $parent
 * @property string $priority
 * @property string $question
 * @property mixed $renderFunc
 * @property boolean $repeatIfNotCompleted
 * @property integer $schedule
 * @property string $section
 * @property integer $sortOrder
 * @property boolean $supercedePrev
 * @property mixed $time
 * @property integer $type
 *
 * @property TaskGroupSetup $group
 * @property Schedule $scheduleObject
 */
class TaskSetup extends Record
{
}
