<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\Schema\ResourceInfo;
use DateTime;

/**
 * @property DateTime $created
 * @property integer $creator
 * @property mixed $data
 * @property boolean $deleted
 * @property string $documentId
 * @property integer $employee
 * @property integer $keyInt
 * @property string $keyString
 * @property string $label
 * @property DateTime $modified
 * @property integer $operationalUnit
 * @property string $permission
 */
class CustomAppData extends Record
{
}
