<?php

namespace CommunityDS\Deputy\Api\Model;

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
 * @property array $validation
 * @property mixed $valuelist
 * @property boolean $visible
 *
 * @property PayRules $customField
 * @property OperationalUnit $operationUnit
 */
class CustomField extends Record
{
    /**
     * Translates the numerical type into the data type defined in the schema.
     *
     * @return string
     */
    public function getDataType()
    {
        return $this->getWrapper()->schema->getCustomFieldDataType($this->type);
    }
}
