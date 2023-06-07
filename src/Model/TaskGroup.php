<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property string $comment
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $date
 * @property DateTime $dayTimestamp
 * @property integer $groupSetupId
 * @property string $key
 * @property boolean $laborModel
 * @property DateTime $modified
 * @property string $name
 * @property integer $opUnitId
 * @property DateTime $origDate
 * @property DateTime $origDayTimestamp
 * @property integer $sortOrder
 *
 * @property TaskGroupSetup $groupSetup
 * @property OperationalUnit $opUnit
 *
 * @property string $displayName
 */
class TaskGroup extends Record
{
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->name;
    }
}
