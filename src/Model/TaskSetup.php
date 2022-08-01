<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $groupId
 * @property integer $type
 * @property integer $parent
 * @property string $question
 * @property string $default
 * @property integer $sortOrder
 * @property integer $schedule
 * @property string $onYes
 * @property string $onNo
 * @property string $renderFunc
 * @property boolean $active
 * @property string $availableAfter
 * @property boolean $repeatIfNotCompleted
 * @property string $time
 * @property string $section
 * @property string $priority
 * @property string $helptext
 * @property boolean $supercedePrev
 * @property string $colour
 * @property string $onStart
 * @property string $onSubmit
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property TaskGroupSetup $group
 * @property Schedule $scheduleObject
 */
class TaskSetup extends Record
{
}
