<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $roster
 * @property integer $employee
 * @property boolean $accepted
 * @property boolean $seen
 * @property boolean $declined
 * @property string $link
 * @property string $message
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Roster $rosterObject
 * @property Employee $employeeObject
 */
class RosterOpen extends Record
{

}
