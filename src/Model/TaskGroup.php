<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $groupSetupId
 * @property string $key
 * @property string $name
 * @property DateTime $dayTimestamp
 * @property DateTime $date
 * @property DateTime $origDayTimestamp
 * @property DateTime $origDate
 * @property integer $opUnitId
 * @property integer $sortOrder
 * @property string $comment
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
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
