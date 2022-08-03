<?php

namespace CommunityDS\Deputy\Api\Model;

use CommunityDS\Deputy\Api\Helper\DateTimeHelper;
use CommunityDS\Deputy\Api\InvalidParamException;
use CommunityDS\Deputy\Api\NotSupportedException;
use DateInterval;
use DateTime;

/**
 * @property integer $id
 * @property integer $employee
 * @property integer $employeeHistory
 * @property integer $employeeAgreement
 * @property DateTime $date
 * @property DateTime $startTime
 * @property DateTime $endTime
 * @property DateTime $mealbreak
 * @property string $mealbreakSlots
 * @property float $totalTime
 * @property float $totalTimeInv
 * @property float $cost
 * @property integer $roster
 * @property string $employeeComment
 * @property string $supervisorComment
 * @property integer $supervisor
 * @property boolean $disputed
 * @property boolean $timeApproved
 * @property integer $timeApprover
 * @property boolean $discarded
 * @property integer $validationFlag
 * @property integer $operationalUnit
 * @property boolean $isInProgress
 * @property boolean $isLeave
 * @property integer $leaveId
 * @property integer $leaveRule
 * @property boolean $invoiced
 * @property string $invoiceComment
 * @property boolean $payRuleApproved
 * @property boolean $exported
 * @property integer $stagingId
 * @property boolean $payStaged
 * @property integer $paycycleId
 * @property integer $file
 * @property integer $customFieldData
 * @property boolean $realTime
 * @property boolean $autoProcessed
 * @property boolean $autoRounded
 * @property boolean $autoTimeApproved
 * @property boolean $autoPayRuleApproved
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Employee $employeeObject
 * @property EmployeeAgreement $employeeAgreementObject
 * @property Roster $rosterObject
 * @property OperationalUnit $operationalUnitObject
 * @property Leave $leave
 * @property LeaveRules $leaveRuleObject
 * @property EmployeePaycycle $paycycle
 * @property CustomFieldData $customFieldDataObject
 *
 * @property integer $mealbreakMinutes
 */
class Timesheet extends Record
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
                    $payload["intEmployeeId"] = $value;
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

        return true;
    }
}
