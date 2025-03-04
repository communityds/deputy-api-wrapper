<?php

namespace CommunityDS\Deputy\Api;

use CommunityDS\Deputy\Api\Adapter\AuthenticationInterface;
use CommunityDS\Deputy\Api\Adapter\CacheInterface;
use CommunityDS\Deputy\Api\Adapter\ClientInterface;
use CommunityDS\Deputy\Api\Adapter\LoggerInterface;
use CommunityDS\Deputy\Api\Adapter\TargetConfigInterface;
use CommunityDS\Deputy\Api\Model\CustomField;
use CommunityDS\Deputy\Api\Model\CustomFieldData;
use CommunityDS\Deputy\Api\Model\Me;
use CommunityDS\Deputy\Api\Schema\Registry;

/**
 * Entry point to interacting with the Deputy API.
 *
 * @method Model\Address createAddress()
 * @method Model\Address deleteAddress($id)
 * @method Model\Address getAddress($id)
 * @method Model\Address[] getAddresses()
 * @method Query findAddresses()
 *
 * @method Model\Category createCategory()
 * @method Model\Category deleteCategory($id)
 * @method Model\Category getCategory($id)
 * @method Model\Category[] getCategories()
 * @method Query findCategories()
 *
 * @method Model\Comment createComment()
 * @method Model\Comment deleteComment($id)
 * @method Model\Comment getComment($id)
 * @method Model\Comment[] getComments()
 * @method Query findComments()
 *
 * @method Model\Company createCompany()
 * @method Model\Company deleteCompany($id)
 * @method Model\Company getCompany($id)
 * @method Model\Company[] getCompanies()
 * @method Query findCompanies()
 *
 * @method Model\CompanyPeriod createCompanyPeriod()
 * @method Model\CompanyPeriod deleteCompanyPeriod($id)
 * @method Model\CompanyPeriod getCompanyPeriod($id)
 * @method Model\CompanyPeriod[] getCompanyPeriods()
 * @method Query findCompanyPeriods()
 *
 * @method Model\Contact createContact()
 * @method Model\Contact deleteContact($id)
 * @method Model\Contact getContact($id)
 * @method Model\Contact[] getContacts()
 * @method Query findContacts()
 *
 * @method Model\Country createCountry()
 * @method Model\Country deleteCountry($id)
 * @method Model\Country getCountry($id)
 * @method Model\Country[] getCountries()
 * @method Query findCountries()
 *
 * @method Model\CustomAppData createCustomAppData()
 * @method Model\CustomAppData deleteCustomAppData($id)
 * @method Model\CustomAppData getCustomAppData($id)
 * @method Model\CustomAppData[] getCustomAppDatas()
 * @method Query findCustomAppDatas()
 *
 * @method Model\CustomField createCustomField()
 * @method Model\CustomField deleteCustomField($id)
 * @method Model\CustomField getCustomField($id)
 * @method Model\CustomField[] getCustomFields()
 * @method Query findCustomFields()
 *
 * @method Model\CustomFieldData createCustomFieldData()
 * @method Model\CustomFieldData deleteCustomFieldData($id)
 * @method Model\CustomFieldData getCustomFieldData($id)
 * @method Model\CustomFieldData[] getCustomFieldDatas()
 * @method Query findCustomFieldDatas()
 *
 * @method Model\Employee createEmployee()
 * @method Model\Employee deleteEmployee($id)
 * @method Model\Employee getEmployee($id)
 * @method Model\Employee[] getEmployees()
 * @method Query findEmployees()
 *
 * @method Model\EmployeeAgreement createEmployeeAgreement()
 * @method Model\EmployeeAgreement deleteEmployeeAgreement($id)
 * @method Model\EmployeeAgreement getEmployeeAgreement($id)
 * @method Model\EmployeeAgreement[] getEmployeeAgreements()
 * @method Query findEmployeeAgreements()
 *
 * @method Model\EmployeeAgreementHistory createEmployeeAgreementHistory()
 * @method Model\EmployeeAgreementHistory deleteEmployeeAgreementHistory($id)
 * @method Model\EmployeeAgreementHistory getEmployeeAgreementHistory($id)
 * @method Model\EmployeeAgreementHistory[] getEmployeeAgreementHistorys()
 * @method Query findEmployeeAgreementHistorys()
 *
 * @method Model\EmployeeAppraisal createEmployeeAppraisal()
 * @method Model\EmployeeAppraisal deleteEmployeeAppraisal($id)
 * @method Model\EmployeeAppraisal getEmployeeAppraisal($id)
 * @method Model\EmployeeAppraisal[] getEmployeeAppraisals()
 * @method Query findEmployeeAppraisals()
 *
 * @method Model\EmployeeAvailability createEmployeeAvailability()
 * @method Model\EmployeeAvailability deleteEmployeeAvailability($id)
 * @method Model\EmployeeAvailability getEmployeeAvailability($id)
 * @method Model\EmployeeAvailability[] getEmployeeAvailabilities()
 * @method Query findEmployeeAvailabilities()
 *
 * @method Model\EmployeeHistory createEmployeeHistory()
 * @method Model\EmployeeHistory deleteEmployeeHistory($id)
 * @method Model\EmployeeHistory getEmployeeHistory($id)
 * @method Model\EmployeeHistory[] getEmployeeHistories()
 * @method Query findEmployeeHistories()
 *
 * @method Model\EmployeePaycycle createEmployeePaycycle()
 * @method Model\EmployeePaycycle deleteEmployeePaycycle($id)
 * @method Model\EmployeePaycycle getEmployeePaycycle($id)
 * @method Model\EmployeePaycycle[] getEmployeePaycycles()
 * @method Query findEmployeePaycycles()
 *
 * @method Model\EmployeePaycycleReturn createEmployeePaycycleReturn()
 * @method Model\EmployeePaycycleReturn deleteEmployeePaycycleReturn($id)
 * @method Model\EmployeePaycycleReturn getEmployeePaycycleReturn($id)
 * @method Model\EmployeePaycycleReturn[] getEmployeePaycycleReturns()
 * @method Query findEmployeePaycycleReturns()
 *
 * @method Model\EmployeeRole createEmployeeRole()
 * @method Model\EmployeeRole deleteEmployeeRole($id)
 * @method Model\EmployeeRole getEmployeeRole($id)
 * @method Model\EmployeeRole[] getEmployeeRoles()
 * @method Query findEmployeeRoles()
 *
 * @method Model\EmployeeSalaryOpunitCosting createEmployeeSalaryOpunitCosting()
 * @method Model\EmployeeSalaryOpunitCosting deleteEmployeeSalaryOpunitCosting($id)
 * @method Model\EmployeeSalaryOpunitCosting getEmployeeSalaryOpunitCosting($id)
 * @method Model\EmployeeSalaryOpunitCosting[] getEmployeeSalaryOpunitCostings()
 * @method Query findEmployeeSalaryOpunitCostings()
 *
 * @method Model\EmployeeWorkplace createEmployeeWorkplace()
 * @method Model\EmployeeWorkplace deleteEmployeeWorkplace($id)
 * @method Model\EmployeeWorkplace getEmployeeWorkplace($id)
 * @method Model\EmployeeWorkplace[] getEmployeeWorkplaces()
 * @method Query findEmployeeWorkplaces()
 *
 * @method Model\EmploymentCondition createEmploymentCondition()
 * @method Model\EmploymentCondition deleteEmploymentCondition($id)
 * @method Model\EmploymentCondition getEmploymentCondition($id)
 * @method Model\EmploymentCondition[] getEmploymentConditions()
 * @method Query findEmploymentConditions()
 *
 * @method Model\EmploymentContract createEmploymentContract()
 * @method Model\EmploymentContract deleteEmploymentContract($id)
 * @method Model\EmploymentContract getEmploymentContract($id)
 * @method Model\EmploymentContract[] getEmploymentContracts()
 * @method Query findEmploymentContracts()
 *
 * @method Model\EmploymentContractLeaveRules createEmploymentContractLeaveRules()
 * @method Model\EmploymentContractLeaveRules deleteEmploymentContractLeaveRules($id)
 * @method Model\EmploymentContractLeaveRules getEmploymentContractLeaveRules($id)
 * @method Query findEmploymentContractLeaveRules()
 *
 * @method Model\Event createEvent()
 * @method Model\Event deleteEvent($id)
 * @method Model\Event getEvent($id)
 * @method Model\Event[] getEvents()
 * @method Query findEvents()
 *
 * @method Model\Geo createGeo()
 * @method Model\Geo deleteGeo($id)
 * @method Model\Geo getGeo($id)
 * @method Model\Geo[] getGeos()
 * @method Query findGeos()
 *
 * @method Model\Journal createJournal()
 * @method Model\Journal deleteJournal($id)
 * @method Model\Journal getJournal($id)
 * @method Model\Journal[] getJournals()
 * @method Query findJournals()
 *
 * @method Model\Kiosk createKiosk()
 * @method Model\Kiosk deleteKiosk($id)
 * @method Model\Kiosk getKiosk($id)
 * @method Model\Kiosk[] getKiosks()
 * @method Query findKiosks()
 *
 * @method Model\Leave createLeave()
 * @method Model\Leave deleteLeave($id)
 * @method Model\Leave getLeave($id)
 * @method Model\Leave[] getLeaves()
 * @method Query findLeaves()
 *
 * @method Model\LeaveAccrual createLeaveAccrual()
 * @method Model\LeaveAccrual deleteLeaveAccrual($id)
 * @method Model\LeaveAccrual getLeaveAccrual($id)
 * @method Model\LeaveAccrual[] getLeaveAccruals()
 * @method Query findLeaveAccruals()
 *
 * @method Model\LeavePayLine createLeavePayLine()
 * @method Model\LeavePayLine deleteLeavePayLine($id)
 * @method Model\LeavePayLine getLeavePayLine($id)
 * @method Model\LeavePayLine[] getLeavePayLines()
 * @method Query findLeavePayLines()
 *
 * @method Model\LeaveRules createLeaveRules()
 * @method Model\LeaveRules deleteLeaveRules($id)
 * @method Model\LeaveRules getLeaveRules($id)
 * @method Query findLeaveRules()
 *
 * @method Model\Memo createMemo()
 * @method Model\Memo deleteMemo($id)
 * @method Model\Memo getMemo($id)
 * @method Model\Memo[] getMemos()
 * @method Query findMemos()
 *
 * @method Model\OperationalUnit createOperationalUnit()
 * @method Model\OperationalUnit deleteOperationalUnit($id)
 * @method Model\OperationalUnit getOperationalUnit($id)
 * @method Model\OperationalUnit[] getOperationalUnits()
 * @method Query findOperationalUnits()
 *
 * @method Model\PayPeriod createPayPeriod()
 * @method Model\PayPeriod deletePayPeriod($id)
 * @method Model\PayPeriod getPayPeriod($id)
 * @method Model\PayPeriod[] getPayPeriods()
 * @method Query findPayPeriods()
 *
 * @method Model\PayRules createPayRules()
 * @method Model\PayRules deletePayRules($id)
 * @method Model\PayRules getPayRules($id)
 * @method Query findPayRules()
 *
 * @method Model\PublicHoliday createPublicHoliday()
 * @method Model\PublicHoliday deletePublicHoliday($id)
 * @method Model\PublicHoliday getPublicHoliday($id)
 * @method Model\PublicHoliday[] getPublicHolidays()
 * @method Query findPublicHolidays()
 *
 * @method Model\Roster createRoster()
 * @method Model\Roster deleteRoster($id)
 * @method Model\Roster getRoster($id)
 * @method Model\Roster[] getRosters()
 * @method Query findRosters()
 *
 * @method Model\RosterOpen createRosterOpen()
 * @method Model\RosterOpen deleteRosterOpen($id)
 * @method Model\RosterOpen getRosterOpen($id)
 * @method Model\RosterOpen[] getRosterOpens()
 * @method Query findRosterOpens()
 *
 * @method Model\RosterSwap createRosterSwap()
 * @method Model\RosterSwap deleteRosterSwap($id)
 * @method Model\RosterSwap getRosterSwap($id)
 * @method Model\RosterSwap[] getRosterSwaps()
 * @method Query findRosterSwaps()
 *
 * @method Model\SalesData createSalesData()
 * @method Model\SalesData deleteSalesData($id)
 * @method Model\SalesData getSalesData($id)
 * @method Query findSalesData()
 *
 * @method Model\Schedule createSchedule()
 * @method Model\Schedule deleteSchedule($id)
 * @method Model\Schedule getSchedule($id)
 * @method Model\Schedule[] getSchedules()
 * @method Query findSchedules()
 *
 * @method Model\ShiftTemplate createShiftTemplate()
 * @method Model\ShiftTemplate deleteShiftTemplate($id)
 * @method Model\ShiftTemplate getShiftTemplate($id)
 * @method Model\ShiftTemplate[] getShiftTemplates()
 * @method Query findShiftTemplates()
 *
 * @method Model\SmsLog createSmsLog()
 * @method Model\SmsLog deleteSmsLog($id)
 * @method Model\SmsLog getSmsLog($id)
 * @method Model\SmsLog[] getSmsLogs()
 * @method Query findSmsLogs()
 *
 * @method Model\State createState()
 * @method Model\State deleteState($id)
 * @method Model\State getState($id)
 * @method Model\State[] getStates()
 * @method Query findStates()
 *
 * @method Model\StressProfile createStressProfile()
 * @method Model\StressProfile deleteStressProfile($id)
 * @method Model\StressProfile getStressProfile($id)
 * @method Model\StressProfile[] getStressProfiles()
 * @method Query findStressProfiles()
 *
 * @method Model\SystemUsageBalance createSystemUsageBalance()
 * @method Model\SystemUsageBalance deleteSystemUsageBalance($id)
 * @method Model\SystemUsageBalance getSystemUsageBalance($id)
 * @method Model\SystemUsageBalance[] getSystemUsageBalances()
 * @method Query findSystemUsageBalances()
 *
 * @method Model\SystemUsageTracking createSystemUsageTracking()
 * @method Model\SystemUsageTracking deleteSystemUsageTracking($id)
 * @method Model\SystemUsageTracking getSystemUsageTracking($id)
 * @method Model\SystemUsageTracking[] getSystemUsageTrackings()
 * @method Query findSystemUsageTrackings()
 *
 * @method Model\Task createTask()
 * @method Model\Task deleteTask($id)
 * @method Model\Task getTask($id)
 * @method Model\Task[] getTasks()
 * @method Query findTasks()
 *
 * @method Model\TaskGroup createTaskGroup()
 * @method Model\TaskGroup deleteTaskGroup($id)
 * @method Model\TaskGroup getTaskGroup($id)
 * @method Model\TaskGroup[] getTaskGroups()
 * @method Query findTaskGroups()
 *
 * @method Model\TaskGroupSetup createTaskGroupSetup()
 * @method Model\TaskGroupSetup deleteTaskGroupSetup($id)
 * @method Model\TaskGroupSetup getTaskGroupSetup($id)
 * @method Model\TaskGroupSetup[] getTaskGroupSetups()
 * @method Query findTaskGroupSetups()
 *
 * @method Model\TaskOpunitConfig createTaskOpunitConfig()
 * @method Model\TaskOpunitConfig deleteTaskOpunitConfig($id)
 * @method Model\TaskOpunitConfig getTaskOpunitConfig($id)
 * @method Model\TaskOpunitConfig[] getTaskOpunitConfigs()
 * @method Query findTaskOpunitConfigs()
 *
 * @method Model\TaskSetup createTaskSetup()
 * @method Model\TaskSetup deleteTaskSetup($id)
 * @method Model\TaskSetup getTaskSetup($id)
 * @method Model\TaskSetup[] getTaskSetups()
 * @method Query findTaskSetups()
 *
 * @method Model\Team createTeam()
 * @method Model\Team deleteTeam($id)
 * @method Model\Team getTeam($id)
 * @method Model\Team[] getTeams()
 * @method Query findTeams()
 *
 * @method Model\Timesheet createTimesheet()
 * @method Model\Timesheet deleteTimesheet($id)
 * @method Model\Timesheet getTimesheet($id)
 * @method Model\Timesheet[] getTimesheets()
 * @method Query findTimesheets()
 *
 * @method Model\TimesheetPayReturn createTimesheetPayReturn()
 * @method Model\TimesheetPayReturn deleteTimesheetPayReturn($id)
 * @method Model\TimesheetPayReturn getTimesheetPayReturn($id)
 * @method Model\TimesheetPayReturn[] getTimesheetPayReturns()
 * @method Query findTimesheetPayReturns()
 *
 * @method Model\TrainingModule createTrainingModule()
 * @method Model\TrainingModule deleteTrainingModule($id)
 * @method Model\TrainingModule getTrainingModule($id)
 * @method Model\TrainingModule[] getTrainingModules()
 * @method Query findTrainingModules()
 *
 * @method Model\TrainingRecord createTrainingRecord()
 * @method Model\TrainingRecord deleteTrainingRecord($id)
 * @method Model\TrainingRecord getTrainingRecord($id)
 * @method Model\TrainingRecord[] getTrainingRecords()
 * @method Query findTrainingRecords()
 *
 * @method Model\User getUser($id)
 *
 * @method Model\Webhook createWebhook()
 * @method Model\Webhook deleteWebhook($id)
 * @method Model\Webhook getWebhook($id)
 * @method Model\Webhook[] getWebhooks()
 * @method Query findWebhooks()
 */
class Wrapper extends Component
{
    /**
     * Singleton instance of this wrapper.
     *
     * @var static
     */
    private static $instance;

    /**
     * Configuration or instance of Authentication mechanism.
     *
     * @var AuthenticationInterface|array
     */
    public $auth;

    /**
     * Configuration or instance of HTTP client.
     *
     * @var ClientInterface|array
     */
    public $client = 'CommunityDS\Deputy\Api\Adapter\Guzzle6\Client';

    /**
     * Configuration or instance of logger interface.
     *
     * @var LoggerInterface
     */
    public $logger = 'CommunityDS\Deputy\Api\Adapter\Logger\NullLogger';

    /**
     * Configuration or instance of persistent Cache.
     *
     * @var CacheInterface|array
     */
    public $persistent = 'CommunityDS\Deputy\Api\Adapter\Native\RuntimeCache';

    /**
     * Configuration or instance of runtime Cache.
     *
     * @var CacheInterface|array
     */
    public $runtime = 'CommunityDS\Deputy\Api\Adapter\Native\RuntimeCache';

    /**
     * Configuration or instance of Schema registry.
     *
     * @var Registry|array
     */
    public $schema = 'CommunityDS\Deputy\Api\Schema\Registry';

    /**
     * Configuration or instance of API target.
     *
     * @var TargetConfigInterface|array
     */
    public $target;

    public function init()
    {
        parent::init();
        $this->auth = Component::createObject($this->auth);
        $this->client = Component::createObject($this->client);
        $this->logger = Component::createObject($this->logger);
        $this->persistent = Component::createObject($this->persistent);
        $this->runtime = Component::createObject($this->runtime);
        $this->schema = Component::createObject($this->schema);
        $this->target = Component::createObject($this->target);
    }

    public function __call($name, $arguments)
    {

        // Return specific instance of resource
        if (preg_match('/^get(.+)$/', $name, $matches)) {
            $resource = $this->schema->resource($matches[1]);
            if ($resource) {
                if (strtolower($resource->getSingularName()) == strtolower($matches[1])) {
                    return $resource->findOne($arguments[0]);
                }
                return $resource->find()->all();
            }
        }

        // Return query for specific instance of resource
        if (preg_match('/^find(.+)$/', $name, $matches)) {
            $resource = $this->schema->resource($matches[1]);
            if ($resource) {
                return $resource->find();
            }
        }

        // Return new instance of object
        if (preg_match('/^create(.+)$/', $name, $matches)) {
            $resource = $this->schema->resource($matches[1]);
            if ($resource) {
                return $resource->create(isset($arguments[0]) ? $arguments[0] : []);
            }
        }

        // Delete instance of object
        if (preg_match('/^delete(.+)$/', $name, $matches)) {
            $resource = $this->schema->resource($matches[1]);
            if ($resource) {
                $instance = $resource->create(['Id' => $arguments[0]]);
                return $instance->delete();
            }
        }


        throw new InvalidParamException('Unknown method ' . $name);
    }

    /**
     * Returns details of the currently logged in user.
     *
     * @return Me
     */
    public function getMe()
    {
        $me = $this->runtime->get('me', null);
        if ($me === null) {
            $me = $this->schema->resource('Me')->findOne('me');
            $this->runtime->set('me', $me);
        }
        return $me;
    }

    /**
     * Returns the cache key for custom fields.
     *
     * @return string
     */
    protected function customFieldsCacheKey()
    {
        return strtolower('resource-customFields-Collection');
    }

    /**
     * Get CustomFields from cache; populate cache if not already set
     *
     * @return CustomField[] Empty array if none found
     */
    public function getCustomFieldsCached()
    {
        $customFields = $this->persistent->get($this->customFieldsCacheKey(), null);
        if ($customFields === null) {
            $customFields = $this->findCustomFields()->all();
            $this->persistent->set($this->customFieldsCacheKey(), $customFields);
        }
        return $customFields;
    }

    /**
     * Get the internal 'deputyfield' (eg. f03) from given 'apiname' (eg. 'casenotes')
     *
     * @param string $apiName Eg. 'casenotes'
     *
     * @return CustomField|null The matching CustomField instance where 'apiName' (eg. 'casenotes') matches param - otherwise, null
     */
    public function getCustomFieldByApiName($apiName)
    {
        foreach ($this->getCustomFieldsCached() as $customField) {
            if ($customField->apiName == $apiName) {
                return $customField;
            }
        }
        return null;
    }

    /**
     * Flushes the custom fields cache.
     *
     * @return $this
     */
    public function flushCustomFieldsCache()
    {
        $this->persistent->remove($this->customFieldsCacheKey());
        CustomFieldData::flushSchema();
        return $this;
    }

    /**
     * Returns the current instance of the API wrapper.
     *
     * @return static|null
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * Sets the current instance of the API wrapper.
     *
     * @param static|array $instance Wrapper instance; or instance configuration
     *
     * @return static Wrapper instance
     */
    public static function setInstance($instance)
    {
        if (is_array($instance)) {
            $instance = new static($instance);
        }
        static::$instance = $instance;
        return $instance;
    }
}
