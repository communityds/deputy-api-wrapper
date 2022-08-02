<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\Schema\ResourceInfo;
use DateTime;

/**
 * @property integer $id
 *
 * @property string $system
 * @property string $f01                DeputyField (Blob)
 * @property string $f02                DeputyField (Blob)
 * @property string $f03                DeputyField (Blob)
 * @property string $f04                DeputyField (Blob)
 * @property string $f05                DeputyField (Blob)
 * @property string $f06                DeputyField (Blob)
 * @property string $f07                DeputyField (Blob)
 * @property string $f08                DeputyField (Blob)
 * @property string $f09                DeputyField (Blob)
 * @property string $f10                DeputyField (Blob)
 * @property string $f11                DeputyField (Blob)
 * @property string $f12                DeputyField (Blob)
 * @property string $f13                DeputyField (Blob)
 * @property string $f14                DeputyField (Blob)
 * @property string $f15                DeputyField (Blob)
 * @property string $f16                DeputyField (Blob)
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
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
