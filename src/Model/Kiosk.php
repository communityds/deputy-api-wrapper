<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $authenticationMode
 * @property integer $company
 * @property integer $connectionMode
 * @property DateTime $created
 * @property integer $creator
 * @property boolean $enableMultiLocations
 * @property string $installationId
 * @property string $ipAddress
 * @property string $lastActivity
 * @property DateTime $modified
 * @property string $name
 * @property mixed $subnetRestriction
 * @property boolean $useBiometric
 *
 * @property Company $companyObject
 */
class Kiosk extends Record
{
}
