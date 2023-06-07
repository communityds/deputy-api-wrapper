<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\Schema\ResourceInfo;
use DateTime;

/**
 * @property DateTime $created
 * @property integer $creator
 * @property mixed $f01
 * @property mixed $f02
 * @property mixed $f03
 * @property mixed $f04
 * @property mixed $f05
 * @property mixed $f06
 * @property mixed $f07
 * @property mixed $f08
 * @property mixed $f09
 * @property mixed $f10
 * @property mixed $f100
 * @property mixed $f101
 * @property mixed $f102
 * @property mixed $f103
 * @property mixed $f104
 * @property mixed $f105
 * @property mixed $f106
 * @property mixed $f107
 * @property mixed $f108
 * @property mixed $f109
 * @property mixed $f11
 * @property mixed $f110
 * @property mixed $f111
 * @property mixed $f112
 * @property mixed $f113
 * @property mixed $f114
 * @property mixed $f115
 * @property mixed $f116
 * @property mixed $f117
 * @property mixed $f118
 * @property mixed $f119
 * @property mixed $f12
 * @property mixed $f120
 * @property mixed $f121
 * @property mixed $f122
 * @property mixed $f123
 * @property mixed $f124
 * @property mixed $f125
 * @property mixed $f126
 * @property mixed $f127
 * @property mixed $f128
 * @property mixed $f13
 * @property mixed $f14
 * @property mixed $f15
 * @property mixed $f16
 * @property mixed $f17
 * @property mixed $f18
 * @property mixed $f19
 * @property mixed $f20
 * @property mixed $f21
 * @property mixed $f22
 * @property mixed $f23
 * @property mixed $f24
 * @property mixed $f25
 * @property mixed $f26
 * @property mixed $f27
 * @property mixed $f28
 * @property mixed $f29
 * @property mixed $f30
 * @property mixed $f31
 * @property mixed $f32
 * @property mixed $f33
 * @property mixed $f34
 * @property mixed $f35
 * @property mixed $f36
 * @property mixed $f37
 * @property mixed $f38
 * @property mixed $f39
 * @property mixed $f40
 * @property mixed $f41
 * @property mixed $f42
 * @property mixed $f43
 * @property mixed $f44
 * @property mixed $f45
 * @property mixed $f46
 * @property mixed $f47
 * @property mixed $f48
 * @property mixed $f49
 * @property mixed $f50
 * @property mixed $f51
 * @property mixed $f52
 * @property mixed $f53
 * @property mixed $f54
 * @property mixed $f55
 * @property mixed $f56
 * @property mixed $f57
 * @property mixed $f58
 * @property mixed $f59
 * @property mixed $f60
 * @property mixed $f61
 * @property mixed $f62
 * @property mixed $f63
 * @property mixed $f64
 * @property mixed $f65
 * @property mixed $f66
 * @property mixed $f67
 * @property mixed $f68
 * @property mixed $f69
 * @property mixed $f70
 * @property mixed $f71
 * @property mixed $f72
 * @property mixed $f73
 * @property mixed $f74
 * @property mixed $f75
 * @property mixed $f76
 * @property mixed $f77
 * @property mixed $f78
 * @property mixed $f79
 * @property mixed $f80
 * @property mixed $f81
 * @property mixed $f82
 * @property mixed $f83
 * @property mixed $f84
 * @property mixed $f85
 * @property mixed $f86
 * @property mixed $f87
 * @property mixed $f88
 * @property mixed $f89
 * @property mixed $f90
 * @property mixed $f91
 * @property mixed $f92
 * @property mixed $f93
 * @property mixed $f94
 * @property mixed $f95
 * @property mixed $f96
 * @property mixed $f97
 * @property mixed $f98
 * @property mixed $f99
 * @property DateTime $modified
 * @property string $system
 */
class CustomFieldData extends Record
{
    /**
     * Support the setting of CustomFieldData by using either:
     * - the fXX property directly (or any other property)
     * - or if the a CustomField 'ApiName' (aka alias) is set then also set the fXX property
     *
     * Example of CustomField properties:
     *  "Name": "Travel Time",
     *  "ApiName": "traveltime",
     *  "DeputyField": "f02",
     *
     * eg. if $var is "traveltime" then ALSO set $this->f02 = $value
     *
     * @param string $var
     * @param mixed $value
     *
     * @return CustomFieldData this instance
     */
    public function __set($var, $value)
    {
        $propertyName = $this->getPropertyName($var);
        if ($var != $propertyName) {
            // Also set the relevant fXX (ie. DeputyField) to match the alias supplied in $var (ie. ApiFieldname)
            $this->$propertyName = $value;
        }

        return parent::__set($var, $value);
    }

    /**
     * Override Record::getSchema() to add other potential customFields configured through UI of Deputy
     * Merge the base fields for CustomFieldData (ie. F01, F02, etc)
     * with any CustomField(s) (eg. traveltime)
     *
     * @return ResourceInfo|null
     */
    public function getSchema()
    {
        $cacheKey = strtolower('resource-customFieldData-schema');
        $schemaRecourceInfo = $this->getWrapper()->persistent->get($cacheKey, null);

        if ($schemaRecourceInfo === null) {
            // Get CustomFieldData Schema fields and update/merge with any Custom Fields
            $schemaRecourceInfo = $this->getWrapper()->schema->resource($this->getResourceName());
            $schemaRecourceInfo->fields = array_merge(
                $this->getSchemaFieldsForCustomFields(),
                $schemaRecourceInfo->fields
            );

            $this->getWrapper()->persistent->set($cacheKey, $schemaRecourceInfo);
        }
        return $schemaRecourceInfo;
    }

    /**
     * Get fields partial for CustomFields
     * an array that merged with 'fields' schema for CustomFieldData
     * eg. ['traveltime' => 'Integer']
     *
     * @return array With format ['<apiName>' => '<DateType Class Name>']
     */
    protected function getSchemaFieldsForCustomFields()
    {
        $schemaPartialForCustomFields = [];
        foreach ($this->getWrapper()->getCustomFieldsCached() as $customField) {
            $dataTypeClassName = end(explode('\\', get_class($customField->getDataType())));
            $schemaPartialForCustomFields[$customField->apiName] = $dataTypeClassName;
        }
        return $schemaPartialForCustomFields;
    }

    /**
     * Get Property Name, also checking customField collection
     *
     * @param string $name Property Name (potentially a CustomField 'apiName'/alias)
     *
     * @return string If property name is found in customField collection then return the customField->deputyField (ie. 'fXX'); otherwise $name
     */
    private function getPropertyName($name)
    {
        $customField = $this->getWrapper()->getCustomFieldByApiName($name);
        if (empty($customField)) {
            return $name;
        }
        // CustomField detected
        return $customField->deputyField;
    }
}
