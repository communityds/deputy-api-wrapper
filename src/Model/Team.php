<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property DateTime $created
 * @property integer $creator
 * @property integer $leaderEmployee
 * @property DateTime $modified
 * @property string $name
 *
 * @property Employee $leaderEmployeeObject
 *
 * @property Employee $employee
 * @property Memo $team
 *
 * @property string $displayName
 */
class Team extends Record
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
