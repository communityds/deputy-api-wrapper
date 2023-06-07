<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property mixed $answer
 * @property DateTime $availableAfterTimestamp
 * @property string $comment
 * @property DateTime $created
 * @property DateTime $date
 * @property DateTime $dayTimestamp
 * @property DateTime $dueDate
 * @property DateTime $dueTimestamp
 * @property integer $duration
 * @property integer $end
 * @property integer $groupId
 * @property DateTime $modified
 * @property integer $opUnitId
 * @property DateTime $origDate
 * @property DateTime $origDayTimestamp
 * @property string $question
 * @property boolean $repeatIfNotCompleted
 * @property integer $sortOrder
 * @property integer $start
 * @property integer $taskSetupId
 * @property integer $tsCompleted
 * @property integer $type
 * @property integer $userEntry
 * @property integer $userPerformTask
 * @property integer $userResponsible
 *
 * @property TaskGroup $group
 * @property OperationalUnit $opUnit
 * @property TaskSetup $taskSetup
 *
 * @property string $displayName
 */
class Task extends Record
{
    /**
     * Returns the question.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->question;
    }
}
