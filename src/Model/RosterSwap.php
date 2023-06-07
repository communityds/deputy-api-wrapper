<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property DateTime $created
 * @property integer $creator
 * @property integer $employee
 * @property DateTime $modified
 * @property string $requestMessage
 * @property string $responseMessage
 * @property integer $sourceRoster
 * @property integer $status
 * @property integer $targetRoster
 *
 * @property Employee $employeeObject
 * @property Roster $sourceRosterObject
 * @property Roster $targetRosterObject
 */
class RosterSwap extends Record
{
}
