<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $name
 * @property string $installationId
 * @property integer $company
 * @property integer $connectionMode
 * @property string $subnetRestriction
 * @property integer $authenticationMode
 * @property boolean $useBiometric
 * @property string $lastActivity
 * @property string $ipAddress
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Company $companyObject
 */
class Kiosk extends Record
{

}
