<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $active
 * @property integer $address
 * @property string $colour
 * @property integer $company
 * @property integer $contact
 * @property DateTime $created
 * @property integer $creator
 * @property float $dailyRosterBudget
 * @property DateTime $modified
 * @property string $operationalUnitName
 * @property integer $operationalUnitType
 * @property integer $parentOperationalUnit
 * @property string $payrollExportName
 * @property integer $rosterActiveHoursSchedule
 * @property integer $rosterSortOrder
 * @property boolean $showOnRoster
 * @property string $workType
 *
 * @property Address $addressObject
 * @property Company $companyObject
 * @property Contact $contactObject
 * @property OperationalUnit $parentOperationalUnitObject
 * @property Schedule $rosterActiveHoursScheduleObject
 *
 * @property EmployeeAgreement $employeeSalaryOpunits
 * @property Employee $managementEmployeeOperationalUnit
 * @property ShiftTemplate $operationUnit
 * @property Event $operationalUnit
 * @property Employee $rosterEmployeeOperationalUnit
 * @property TaskGroupSetup $taskGroupOpUnit
 * @property TrainingModule $trainingModule
 *
 * @property string $displayName
 */
class OperationalUnit extends Record
{
    /**
     * Returns the operational unit name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->operationalUnitName;
    }

    /**
     * Creates a new roster for this operational unit.
     *
     * @return OperationalUnit
     */
    public function createRoster()
    {
        $roster = $this->getWrapper()->createRoster();
        $roster->operationalUnit = $this->id;
        return $roster;
    }
}
