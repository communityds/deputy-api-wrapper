<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\Helper\DateTimeHelper;
use CommunityDS\Deputy\Api\InvalidParamException;
use DateInterval;
use DateTime;

/**
 * @property boolean $approvalRequired
 * @property string $comment
 * @property integer $confirmBy
 * @property string $confirmComment
 * @property integer $confirmStatus
 * @property integer $confirmTime
 * @property integer $connectStatus
 * @property float $cost
 * @property DateTime $created
 * @property integer $creator
 * @property integer $customFieldData
 * @property DateTime $date
 * @property integer $employee
 * @property DateTime $endTime
 * @property integer $matchedByTimesheet
 * @property DateTime $mealbreak
 * @property DateTime $modified
 * @property boolean $open
 * @property integer $operationalUnit
 * @property integer $parentId
 * @property boolean $published
 * @property integer $shiftTemplate
 * @property array $slots
 * @property DateTime $startTime
 * @property integer $swapManageBy
 * @property integer $swapStatus
 * @property float $totalTime
 * @property string $warning
 * @property string $warningOverrideComment
 *
 * @property Employee $confirmByObject
 * @property CustomFieldData $customFieldDataObject
 * @property Employee $employeeObject
 * @property Timesheet $matchedByTimesheetObject
 * @property OperationalUnit $operationalUnitObject
 * @property Roster $parent
 * @property ShiftTemplate $shiftTemplateObject
 */
class Roster extends Record
{
    /**
     * Returns the number of minutes allocated for the mealbreak.
     *
     * @return integer|null
     */
    public function getMealbreakMinutes()
    {
        $value = $this->mealbreak;
        if ($value) {
            return DateTimeHelper::getMinutesFromMidnight($value);
        }
        return null;
    }

    /**
     * Sets the `mealbreak` property by converting the number of minutes
     * into a `DateTime` instance relative to midnight on the start time.
     *
     * @param integer $minutes Number of minutes
     *
     * @return void
     *
     * @throws InvalidParamException When start time is not a `DateTime` instance
     */
    public function setMealbreakMinutes($minutes)
    {
        $startTime = $this->startTime;
        if ($startTime == null || !($startTime instanceof DateTime)) {
            throw new InvalidParamException('Start time must be a date time instance');
        }
        $this->mealbreak = DateTimeHelper::getMidnight($startTime);
        $this->mealbreak->add(new DateInterval('PT' . $minutes . 'M'));
    }

    /**
     * Creates new Roster resource via the POST /supervise/roster endpoint,
     * and then sends any remaining attributes to POST /resource/Roster/:id endpoint.
     *
     * @param string[] $attributeNames Names of attributes to store; or null to use
     *                                 only populated attributes.
     *
     * @return boolean
     */
    public function insert($attributeNames = null)
    {
        return $this->postSuperviseRoster(
            $this->insertPayload($attributeNames)
        );
    }

    /**
     * Updates an existing Roster resource via the POST /supervise/roster endpoint,
     * and then sends any remaining attributes to POST /resource/Roster/:id endpoint.
     *
     * @param string[] $attributeNames Names of attributes to store; or null to use
     *                                 only populated attributes.
     *
     * @return boolean
     */
    public function update($attributeNames = null)
    {
        return $this->postSuperviseRoster(
            $this->updatePayload($attributeNames)
        );
    }

    /**
     * Check for related Timesheet and that it is not discarded
     *
     * @return boolean
     */
    public function isTimesheetCreated()
    {
        $timesheetAvailable = false;
        if (is_int($this->matchedByTimesheet) && $this->matchedByTimesheetObject instanceof Timesheet) {
            if ($this->matchedByTimesheetObject->discarded == false) {
                $timesheetAvailable = true;
            }
        }
        return $timesheetAvailable;
    }

    /**
     * Sends a payload to the POST /supervise/roster endpoint
     * and then sends remaining payload to POST /resource/Roster/:id endpoint.
     *
     * @param array $original Original payload
     *
     * @return boolean
     */
    protected function postSuperviseRoster($original)
    {

        $payload = [];
        if ($this->getPrimaryKey()) {
            $payload['intRosterId'] = $this->getPrimaryKey();
            /*
             * Default to the current instance values
             * otherwise, API would give error of "Date must be given." and/or remove the Employee or Comment
             */
            $payload['intStartTimestamp']   = $this->getSchema()->fieldDataType('startTime')->toApi($this->startTime);
            $payload['intEndTimestamp']     = $this->getSchema()->fieldDataType('endTime')->toApi($this->endTime);
            $payload['intRosterEmployee']   = $this->getSchema()->fieldDataType('employee')->toApi($this->employee);
            $payload['strComment']          = $this->getSchema()->fieldDataType('comment')->toApi($this->comment);
        } else {
            $payload['intRosterEmployee'] = 0;
            $payload['blnPublish'] = false;
        }

        foreach ($original as $name => $value) {
            switch ($name) {
                case 'StartTime':
                    $payload['intStartTimestamp'] = $value;
                    unset($original[$name]);
                    break;
                case 'EndTime':
                    $payload['intEndTimestamp'] = $value;
                    unset($original[$name]);
                    break;
                case 'Employee':
                    $payload['intRosterEmployee'] = $value;
                    unset($original[$name]);
                    break;
                case 'Published':
                    $payload['blnPublish'] = $value;
                    unset($original[$name]);
                    break;
                case 'Mealbreak':
                    $payload['intMealbreakMinute'] = DateTimeHelper::getMinutesFromMidnight(
                        new DateTime($value)
                    );
                    unset($original[$name]);
                    break;
                case 'OperationalUnit':
                    $payload['intOpunitId'] = $value;
                    unset($original[$name]);
                    break;
            }
        }

        $response = $this->getWrapper()->client->post(
            'supervise/roster',
            $payload
        );
        if ($response === false) {
            $this->setErrorsFromResponse($this->getWrapper()->client->getLastError());
            return false;
        }

        static::populateRecord($this, $response);

        // Determine additional fields that the supervise/roster endpoint
        // does not support and if any are found then change their value
        // within the model and force an update.

        $updateRecord = false;
        foreach ($original as $key => $value) {
            if (array_key_exists($key, $response)) {
                if ($response[$key] != $value) {
                    $this->{$key} = $value;
                    $updateRecord = true;
                }
            } elseif (!array_key_exists($key, $response)) {
                $this->{$key} = $value;
                $updateRecord = true;
            }
        }

        if ($updateRecord) {
            return parent::update();
        }

        return true;
    }
}
