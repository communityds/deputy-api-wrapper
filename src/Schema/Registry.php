<?php

namespace CommunityDS\Deputy\Api\Schema;

use CommunityDS\Deputy\Api\Component;
use CommunityDS\Deputy\Api\Model\Me;
use CommunityDS\Deputy\Api\Model\User;
use CommunityDS\Deputy\Api\Schema\DataTypeInterface;
use CommunityDS\Deputy\Api\Wrapper;

/**
 * Registry of the Resources and Data Types that the Schema supports.
 */
class Registry extends Component
{
    /**
     * List of resources where key is Resource name and value is model
     * class name or configuration array of used to represent the resource.
     *
     * @var string[]
     */
    public $resources = [];

    /**
     * List of data types where key Data Type name and value is
     * class name or configuration array of DataTypeInterface class.
     *
     * @var string[]
     */
    public $dataTypes = [];

    public function init()
    {
        parent::init();
        $this->resources = array_merge(
            $this->baseResources(),
            $this->resources
        );
        $this->dataTypes = array_merge(
            $this->baseDataTypes(),
            $this->dataTypes
        );
    }

    /**
     * Returns the schema for a particular resource.
     *
     * @param string $name Name of the resource
     * @param boolean $cache If enabled, warms and updates the persistent cache
     *
     * @return ResourceInfo|null
     */
    public function resource($name, $cache = true)
    {
        $key = strtolower('resource-' . $name);
        /* @var ResourceInfo $resource */
        $resource = $this->getWrapper()->persistent->get($key, null);
        if ($resource === null) {
            // Found exact match
            if (array_key_exists($name, $this->resources)) {
                $resource = Component::createObject(
                    array_merge(
                        [
                            'class' => __NAMESPACE__ . '\ResourceInfo',
                            'name' => $name,
                        ],
                        $this->resources[$name]
                    )
                );
                if ($cache) {
                    if ($resource->warm == false) {
                        $resource->loadDefinitions();
                    }
                    $this->getWrapper()->persistent->set($key, $resource);
                }

            // Look for plural alias or case-insensitive match
            } else {
                $aliasKey = strtolower('resource-alias-' . $name);
                $aliasName = $this->getWrapper()->persistent->get($aliasKey, null);

                if ($aliasName === null) {
                    $nameLower = strtolower($name);
                    foreach ($this->resourceNames() as $resourceName) {
                        $resource = $this->resource($resourceName, false);
                        if (strtolower($resource->getSingularName()) == $nameLower) {
                            $aliasName = $resourceName;
                            break;
                        } elseif (strtolower($resource->getPluralName()) == $nameLower) {
                            $aliasName = $resourceName;
                            break;
                        }
                    }
                    if ($aliasName === null) {
                        $aliasName = false;
                    }
                    $this->getWrapper()->persistent->set($aliasKey, $aliasName);
                    $resource = null;
                }

                if ($aliasName) {
                    $resource = $this->resource($aliasName, $cache);
                }
            }
        }
        return $resource;
    }

    /**
     * Returns the names of all defined resources.
     *
     * @return string[]
     */
    public function resourceNames()
    {
        return array_keys($this->resources);
    }

    /**
     * Returns the helper for a particular data type.
     *
     * @param string $name Name of data type
     *
     * @return DataTypeInterface|null
     */
    public function dataType($name)
    {
        $dataType = $this->getWrapper()->runtime->get('datatype-' . $name, null);
        if ($dataType === null && array_key_exists($name, $this->dataTypes)) {
            $dataType = Component::createObject($this->dataTypes[$name]);
            $this->getWrapper()->runtime->set('datatype-' . $name, $dataType);
        }
        return $dataType;
    }

    /**
     * Returns a list of standard Resource types and the model classes
     * used to represent the resource.
     *
     * @return string[]
     */
    public function baseResources()
    {
        return [
            'Address' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Address',
                'pluralName' => 'Addresses',
            ],
            'Category' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Category',
                'pluralName' => 'Categories',
            ],
            'Comment' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Comment',
            ],
            'Company' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Company',
                'pluralName' => 'Companies',
            ],
            'CompanyPeriod' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\CompanyPeriod',
            ],
            'Contact' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Contact',
            ],
            'Country' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Country',
                'pluralName' => 'Countries',
            ],
            'CustomField' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\CustomField',
            ],
            'CustomFieldData' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\CustomFieldData',
            ],
            'Employee' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Employee',
            ],
            'EmployeeAgreement' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\EmployeeAgreement',
            ],
            'EmployeeAgreementHistory' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\EmployeeAgreementHistory',
            ],
            'EmployeeAppraisal' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\EmployeeAppraisal',
            ],
            'EmployeeAvailability' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\EmployeeAvailability',
                'pluralName' => 'EmployeeAvailabilities',
                'fields' => [
                    'StartTime' => 'UnixTimestamp',
                    'EndTime' => 'UnixTimestamp',
                ],
            ],
            'EmployeeHistory' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\EmployeeHistory',
                'pluralName' => 'EmployeeHistories',
            ],
            'EmployeePaycycle' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\EmployeePaycycle',
            ],
            'EmployeePaycycleReturn' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\EmployeePaycycleReturn',
            ],
            'EmployeeRole' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\EmployeeRole',
            ],
            'EmployeeSalaryOpunitCosting' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\EmployeeSalaryOpunitCosting',
            ],
            'EmploymentCondition' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\EmploymentCondition',
            ],
            'EmploymentContract' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\EmploymentContract',
            ],
            'EmploymentContractLeaveRules' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\EmploymentContractLeaveRules',
                'pluralName' => 'EmploymentContractLeaveRules',
            ],
            'Event' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Event',
            ],
            'Geo' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Geo',
                'fields' => [
                    'Longitude' => 'Float',
                    'Latitude' => 'Float',
                ],
            ],
            'Journal' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Journal',
            ],
            'Kiosk' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Kiosk',
            ],
            'Leave' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Leave',
            ],
            'LeaveAccrual' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\LeaveAccrual',
            ],
            'LeavePayLine' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\LeavePayLine',
            ],
            'LeaveRules' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\LeaveRules',
                'pluralName' => 'LeaveRules',
            ],
            'Me' => array_merge(
                [
                    'endpoints' => [
                        'id' => 'me',
                    ],
                    'modelClass' => 'CommunityDS\Deputy\Api\Model\Me',
                    'warm' => true,
                ],
                Me::getResourceConfig()
            ),
            'Memo' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Memo',
            ],
            'OperationalUnit' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\OperationalUnit',
            ],
            'PayPeriod' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\PayPeriod',
            ],
            'PayRules' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\PayRules',
                'pluralName' => 'PayRules',
            ],
            'PublicHoliday' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\PublicHoliday',
            ],
            'Roster' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Roster',
                'fields' => [
                    'StartTime' => 'UnixTimestamp',
                    'EndTime' => 'UnixTimestamp',
                ],
            ],
            'RosterOpen' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\RosterOpen',
            ],
            'RosterSwap' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\RosterSwap',
            ],
            'SalesData' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\SalesData',
                'pluralName' => 'SalesData',
                'fields' => [
                    'Timestamp' => 'UnixTimestamp',
                ],
            ],
            'Schedule' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Schedule',
            ],
            'SmsLog' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\SmsLog',
            ],
            'State' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\State',
            ],
            'StressProfile' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\StressProfile',
            ],
            'SystemUsageBalance' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\SystemUsageBalance',
            ],
            'SystemUsageTracking' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\SystemUsageTracking',
            ],
            'Task' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Task',
                'fields' => [
                    'DayTimestamp' => 'UnixTimestamp',
                    'OrigDayTimestamp' => 'UnixTimestamp',
                    'AvailableAfterTimestamp' => 'UnixTimestamp',
                    'DueTimestamp' => 'UnixTimestamp',
                ],
            ],
            'TaskGroup' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\TaskGroup',
                'fields' => [
                    'DayTimestamp' => 'UnixTimestamp',
                    'OrigDayTimestamp' => 'UnixTimestamp',
                ],
            ],
            'TaskGroupSetup' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\TaskGroupSetup',
            ],
            'TaskOpunitConfig' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\TaskOpunitConfig',
            ],
            'TaskSetup' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\TaskSetup',
            ],
            'Team' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Team',
            ],
            'Timesheet' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Timesheet',
                'fields' => [
                    'StartTime' => 'UnixTimestamp',
                    'EndTime' => 'UnixTimestamp',
                ],
            ],
            'TimesheetPayReturn' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\TimesheetPayReturn',
            ],
            'TrainingModule' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\TrainingModule',
            ],
            'TrainingRecord' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\TrainingRecord',
            ],
            'User' => array_merge(
                [
                    'endpoints' => [
                        'id' => 'userinfo/:Id',
                    ],
                    'modelClass' => 'CommunityDS\Deputy\Api\Model\User',
                    'warm' => true,
                ],
                User::getResourceConfig()
            ),
            'Webhook' => [
                'modelClass' => 'CommunityDS\Deputy\Api\Model\Webhook',
            ],
        ];
    }

    /**
     * Returns a list of standard Data types and the data type classes
     * used to manipulate the data.
     *
     * @return string[]
     */
    public function baseDataTypes()
    {
        return [
            'Bit'           => 'CommunityDS\Deputy\Api\Schema\DataType\Bit',
            'Blob'          => 'CommunityDS\Deputy\Api\Schema\DataType\Blob',
            'Date'          => 'CommunityDS\Deputy\Api\Schema\DataType\Date',
            'DateTime'      => 'CommunityDS\Deputy\Api\Schema\DataType\DateTime',
            'Float'         => 'CommunityDS\Deputy\Api\Schema\DataType\FloatingPoint',
            'Integer'       => 'CommunityDS\Deputy\Api\Schema\DataType\Integer',
            'Time'          => 'CommunityDS\Deputy\Api\Schema\DataType\Time',
            'UnixTimestamp' => 'CommunityDS\Deputy\Api\Schema\DataType\UnixTimestamp',
            'VarChar'       => 'CommunityDS\Deputy\Api\Schema\DataType\VarChar',
            'VarCharArray'  => 'CommunityDS\Deputy\Api\Schema\DataType\VarCharArray',
        ];
    }

    /**
     * Helper to translate from Deputy type id (as returned by the API) to the name of the relevant DataType Class Name
     * eg.
     * 0 => 'Varchar'
     * 2 => 'Integer'
     *
     * @param integer $typeId
     *
     * @return DataTypeInterface|null Instance of DataType or null if not found
     */
    public static function getDataTypeById($typeId)
    {
        $typeIdToDataTypeClassNameMap = [
            1 => 'VarChar',         // Text
            2 => 'Integer',         // Number
            3 => 'VarChar',         // Large text
            4 => 'Bit',             // Boolean/Checkbox
            5 => 'VarCharArray',    // List
            6 => 'VarCharArray',    // Multi list
            7 => 'Blob',            // File
        ];

        $dataTypeClassName = key_exists($typeId, $typeIdToDataTypeClassNameMap) ? $typeIdToDataTypeClassNameMap[$typeId] : null;
        if (empty($dataTypeClassName)) {
            return null;
        }
        $dataTypeClass = "\\CommunityDS\\Deputy\\Api\\Schema\\DataType\\{$dataTypeClassName}";
        return new $dataTypeClass();
    }

    /**
     * Returns wrapper instance.
     *
     * @return Wrapper
     */
    protected function getWrapper()
    {
        return Wrapper::getInstance();
    }
}
