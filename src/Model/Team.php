<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $leaderEmployee
 * @property string $name
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Employee $leaderEmployeeObject
 *
 * @property Memo $team
 * @property Employee $employee
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
