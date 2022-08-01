<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\Schema\DataType\Integer;
use CommunityDS\Deputy\Api\Schema\Registry;
use DateTime;

/**
 * @property integer $id
 * @property string $system
 * @property string $name
 * @property string $apiName
 * @property string $deputyField The corresponding property used on CustomFieldData
 * @property integer $sortOrder
 * @property string $default
 * @property integer $type ID number of the Data Type
 * @property string $valueList
 * @property string $validation
 * @property string $helpText
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 */
class CustomField extends Record
{
    public function getDataType()
    {
        return Registry::getDataTypeById($this->type);
    }
}
