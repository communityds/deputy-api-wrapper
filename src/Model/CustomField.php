<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\Schema\DataType\Integer;
use CommunityDS\Deputy\Api\Schema\Registry;
use DateTime;

/**
 * @property integer $action
 * @property string $apiName
 * @property string $conditionalRules
 * @property DateTime $created
 * @property integer $creator
 * @property string $default
 * @property string $deputyField
 * @property integer $displayTiming
 * @property string $helptext
 * @property DateTime $modified
 * @property string $name
 * @property integer $published
 * @property integer $sortOrder
 * @property string $system
 * @property integer $triggerScript
 * @property integer $type
 * @property string $validation
 * @property string $valuelist
 * @property boolean $visible
 *
 * @property EmploymentContract $customField
 * @property OperationalUnit $operationUnit
 */
class CustomField extends Record
{
    public function getDataType()
    {
        return Registry::getDataTypeById($this->type);
    }
}
