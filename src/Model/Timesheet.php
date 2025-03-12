<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\Helper\DateTimeHelper;
use CommunityDS\Deputy\Api\InvalidParamException;
use CommunityDS\Deputy\Api\NotSupportedException;
use DateInterval;
use DateTime;

/**
 * @property integer $accrualAttempts
 * @property integer $accrualState
 * @property DateTime $accrualStateChangedAt
 * @property boolean $autoPayRuleApproved
 * @property boolean $autoProcessed
 * @property boolean $autoRounded
 * @property boolean $autoTimeApproved
 * @property float $cost
 * @property DateTime $created
 * @property integer $creator
 * @property integer $customFieldData
 * @property DateTime $date
 * @property boolean $discarded
 * @property boolean $disputed
 * @property integer $employee
 * @property integer $employeeAgreement
 * @property string $employeeComment
 * @property integer $employeeHistory
 * @property DateTime $endTime
 * @property boolean $exported
 * @property integer $file
 * @property string $invoiceComment
 * @property boolean $invoiced
 * @property boolean $isInProgress
 * @property boolean $isLeave
 * @property integer $leaveId
 * @property integer $leaveRule
 * @property DateTime $markedPaidUnpaidAt
 * @property DateTime $mealbreak
 * @property array $mealbreakSlots
 * @property mixed $metadata
 * @property DateTime $modified
 * @property integer $operationalUnit
 * @property integer $parentId
 * @property boolean $payRuleApproved
 * @property boolean $payStaged
 * @property integer $paycycleId
 * @property boolean $realTime
 * @property integer $reviewState
 * @property integer $roster
 * @property array $slots
 * @property integer $stagingId
 * @property DateTime $startTime
 * @property integer $supervisor
 * @property string $supervisorComment
 * @property boolean $timeApproved
 * @property integer $timeApprover
 * @property float $totalTime
 * @property float $totalTimeInv
 * @property integer $validationFlag
 *
 * @property CustomFieldData $customFieldDataObject
 * @property EmployeeAgreement $employeeAgreementObject
 * @property Employee $employeeObject
 * @property Leave $leave
 * @property LeaveRules $leaveRuleObject
 * @property OperationalUnit $operationalUnitObject
 * @property Timesheet $parent
 * @property EmployeePaycycle $paycycle
 * @property Roster $rosterObject
 *
 * @property TimesheetExport $export
 *
 * @property integer $mealbreakMinutes
 */
class Timesheet extends Record
{
    /**
     * Sets the values of custom fields that are linked to this timesheet.
     *
     * @param array $values Key as api name; value as field value
     *
     * @return $this
     */
    public function withCustomFields($values)
    {
        $customFieldData = $this->customFieldDataObject;
        if ($customFieldData == null) {
            $customFieldData = $this->getWrapper()->createCustomFieldData();
            $customFieldData->system = 'Timesheet';
        }

        foreach ($values as $name => $value) {
            $customFieldData->{$name} = $value;
        }

        $customFieldData->save();
        $this->customFieldData = $customFieldData->id;

        return $this;
    }

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
     * @return $this
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

        return $this;
    }

    public function insert($attributeNames = null)
    {
        return $this->postSuperviseTimesheet(
            $this->insertPayload($attributeNames)
        );
    }

    public function update($attributeNames = null)
    {
        throw new NotSupportedException('Updating existing timesheet records not yet supported');
    }

    /**
     * Sends a payload to the POST /supervise/timesheet/update endpoint.
     *
     * @param array $original Original payload
     * @param TimesheetBreak[] $breaks Breaks to record
     *
     * @return boolean
     *
     * @throws NotSupportedException When updating existing record
     */
    protected function postSuperviseTimesheet($original, $breaks = [])
    {
        $payload = [
            'strComment' => '.', // Comments are required
        ];

        if ($this->getPrimaryKey()) {
            $payload['intTimesheetId'] = $this->getPrimaryKey();
        }

        foreach ($original as $name => $value) {
            switch ($name) {
                case 'Employee':
                    $payload['intEmployeeId'] = $value;
                    unset($original[$name]);
                    break;
                case 'OperationalUnit':
                    $payload['intOpunitId'] = $value;
                    unset($original[$name]);
                    break;
                case 'StartTime':
                    $payload['intStartTimestamp'] = $value;
                    unset($original[$name]);
                    break;
                case 'EndTime':
                    $payload['intEndTimestamp'] = $value;
                    unset($original[$name]);
                    break;
                case 'Mealbreak':
                    $payload['intMealbreakMinute'] = $this->mealbreakMinutes;
                    unset($original[$name]);
                    break;
                case 'EmployeeContent':
                    $payload['strComment'] = $value;
                    unset($original[$name]);
                    break;
            }
        }

        if (!isset($payload['intEmployeeId'])) {
            $payload['intEmployeeId'] = $this->getWrapper()->getMe()->userId;
        }

        $response = $this->getWrapper()->client->post(
            'supervise/timesheet/update',
            $payload
        );
        if ($response === false) {
            $this->setErrorsFromResponse($this->getWrapper()->client->getLastError());
            return false;
        }

        static::populateRecord($this, $response);

        // Determine additional fields that the supervise/timesheet/update endpoint
        // does not support and if any are found then change their value
        // within the model and force an update.

        $updateRecord = false;
        foreach ($original as $key => $value) {
            if (array_key_exists($key, $response)) {
                if ($response[$key] != $value) {
                    $this->{$key} = $value;
                    $updateRecord = true;
                }
            } else {
                $this->{$key} = $value;
                $updateRecord = true;
            }
        }

        if ($updateRecord) {
            return parent::update();
        }

        return true;
    }

    /**
     * Approves the timesheet.
     *
     * @return boolean True if approved successfully; false otherwise
     *
     * @throws NotSupportedException When attempting to approve a new timesheet
     */
    public function approve()
    {
        if ($this->getPrimaryKey() == null) {
            throw new NotSupportedException('Approvals can only be on existing timesheet records');
        }

        $response = $this->getWrapper()->client->post(
            'supervise/timesheet/approve',
            [
                'intTimesheetId' => $this->getPrimaryKey(),
            ]
        );
        if ($response === false) {
            $this->setErrorsFromResponse($this->getWrapper()->client->getLastError());
            return false;
        }

        return true;
    }
}
