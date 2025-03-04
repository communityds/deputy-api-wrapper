<?php

namespace CommunityDS\Deputy\Api\Model;

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
    public function __set($name, $value)
    {
        // Support the setting of CustomFieldData by using either:
        // - the fXX property directly (or any other property)
        // - or if the a CustomField 'ApiName' (aka alias) is set then also set the fXX property
        //
        // Example of CustomField properties:
        //  "Name": "Travel Time",
        //  "ApiName": "traveltime",
        //  "DeputyField": "f02",
        //
        // eg. if $var is "traveltime" then ALSO set $this->f02 = $value

        $propertyName = $this->getDeputyFieldName($name);
        if ($propertyName) {
            // Also set the relevant fXX (ie. DeputyField) to match the alias supplied in $var (ie. ApiFieldname)
            $this->$propertyName = $value;
        }

        parent::__set($name, $value);
    }

    public function getSchema()
    {
        // Override to add other potential customFields configured through UI of Deputy
        // that are included in CustomFieldData results.
        // e.g. {"Id":3,"System":"Timesheet","F01":3,"Creator":1,"Created":"...","Modified":"...","traveltime":3}

        // Merge the base fields for CustomFieldData (ie. F01, F02, etc)
        // with any CustomField(s) (eg. traveltime).

        $schemaResourceInfo = $this->getWrapper()->persistent->get(static::schemaCacheKey(), null);

        if ($schemaResourceInfo === null) {
            // Get CustomFieldData Schema fields and update/merge with any Custom Fields
            $schemaResourceInfo = parent::getSchema();
            $schemaResourceInfo->fields = array_merge(
                $this->getSchemaFieldsForCustomFields(),
                $schemaResourceInfo->fields
            );

            $this->getWrapper()->persistent->set(static::schemaCacheKey(), $schemaResourceInfo);
        }
        return $schemaResourceInfo;
    }

    /**
     * Get fields partial for CustomFields
     * an array that merged with 'fields' schema for CustomFieldData
     * eg. ['traveltime' => 'Integer']
     *
     * @return array With format ['<apiName>' => '<DateType Name>']
     */
    protected function getSchemaFieldsForCustomFields()
    {
        $schemaPartialForCustomFields = [];
        foreach ($this->getWrapper()->getCustomFieldsCached() as $customField) {
            $dataType = $customField->getDataType();
            if ($dataType) {
                $schemaPartialForCustomFields[$customField->apiName] = $dataType;
            }
        }
        return $schemaPartialForCustomFields;
    }

    /**
     * Get Property Name, also checking customField collection
     *
     * @param string $name Property name
     *
     * @return string|null Returns the deputyField property for a customField that matches the given name; or null if not found
     */
    protected function getDeputyFieldName($name)
    {
        $customField = $this->getWrapper()->getCustomFieldByApiName($name);
        if ($customField == null) {
            return null;
        }
        return $customField->deputyField;
    }

    /**
     * Returns the key of the schema cache.
     *
     * @return string
     */
    protected static function schemaCacheKey()
    {
        return strtolower('resource-customFieldData-schema');
    }

    /**
     * Flushes the schema cache to ensure custom field schemas are updated.
     */
    public static function flushSchema()
    {
        static::getWrapperStatic()->persistent->remove(static::schemaCacheKey());
    }
}
