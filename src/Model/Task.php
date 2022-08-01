<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $taskSetupId
 * @property integer $opUnitId
 * @property integer $groupId
 * @property DateTime $dayTimestamp
 * @property DateTime $date
 * @property DateTime $origDayTimestamp
 * @property DateTime $origDate
 * @property DateTime $availableAfterTimestamp
 * @property DateTime $dueDate
 * @property DateTime $dueTimestamp
 * @property boolean $repeatIfNotCompleted
 * @property integer $sortOrder
 * @property integer $type
 * @property string $question
 * @property string $answer
 * @property string $comment
 * @property integer $userEntry
 * @property integer $userPerformTask
 * @property integer $userResponsible
 * @property DateTime $created
 * @property DateTime $modified
 * @property integer $tsCompleted
 * @property integer $start
 * @property integer $end
 *
 * @property TaskSetup $taskSetup
 * @property OperationalUnit $opUnit
 * @property TaskGroup $group
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
