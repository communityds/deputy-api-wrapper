<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $sourceRoster
 * @property integer $targetRoster
 * @property integer $employee
 * @property integer $status
 * @property string $requestMessage
 * @property string $responseMessage
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Roster $sourceRosterObject
 * @property Roster $targetRosterObject
 * @property Employee $employeeObject
 */
class RosterSwap extends Record
{
}
