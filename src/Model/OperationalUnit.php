<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 * @property integer $company
 * @property string $workType
 * @property integer $parentOperationalUnit
 * @property string $operationalUnitName
 * @property boolean $active
 * @property string $payrollExportName
 * @property integer $address
 * @property integer $contact
 * @property integer $rosterSortOrder
 * @property boolean $showOnRoster
 * @property string $colour
 * @property integer $rosterActiveHoursSchedule
 * @property float $dailyRosterBudget
 * @property integer $operationalUnitType
 *
 * @property Company $companyObject
 * @property OperationalUnit $parentOperationalUnitObject
 * @property Address $addressObject
 * @property Contact $contactObject
 * @property Schedule $rosterActiveHoursScheduleObject
 *
 * @property string $companyCode
 * @property string $companyName
 * @property PublicHoliday $operationUnit
 * @property EmployeeAgreement $employeeSalaryOpunits
 * @property Event $operationalUnit
 * @property Employee $managementEmployeeOperationalUnit
 * @property TrainingModule $trainingModule
 * @property Employee $rosterEmployeeOperationalUnit
 * @property TaskGroupSetup $taskGroupOpUnit
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
