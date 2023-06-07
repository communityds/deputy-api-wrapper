<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $accepted
 * @property DateTime $created
 * @property integer $creator
 * @property boolean $declined
 * @property integer $employee
 * @property string $link
 * @property string $message
 * @property DateTime $modified
 * @property integer $roster
 * @property boolean $seen
 *
 * @property Employee $employeeObject
 * @property Roster $rosterObject
 */
class RosterOpen extends Record
{
}
