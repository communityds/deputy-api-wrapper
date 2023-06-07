<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $annualRollOver
 * @property mixed $calc
 * @property integer $calcType
 * @property DateTime $created
 * @property integer $creator
 * @property mixed $description
 * @property integer $entitlementAfterMonth
 * @property integer $exportType
 * @property mixed $f00
 * @property mixed $f01
 * @property mixed $f02
 * @property mixed $f03
 * @property mixed $f04
 * @property mixed $f05
 * @property mixed $f06
 * @property mixed $f07
 * @property mixed $f08
 * @property mixed $f09
 * @property float $maxAllowedAnnually
 * @property DateTime $modified
 * @property string $name
 * @property boolean $paidLeave
 * @property boolean $payoutOnTermination
 * @property string $payrollCategory
 * @property integer $resetSchedule
 * @property integer $resetType
 * @property float $resetValue
 * @property string $svcLeavePolicyId
 * @property string $type
 * @property integer $unitType
 * @property boolean $visible
 *
 * @property Schedule $resetScheduleObject
 */
class LeaveRules extends Record
{
    /**
     * Do not export for Payroll export type.
     */
    const EXPORT_TYPE_DO_NOT_EXPORT = 0;

    /**
     * Export using associated Pay Condition for employee export type.
     */
    const EXPORT_TYPE_PAY_CONDITION = 1;

    /**
     * Export using Export Code export type.
     */
    const EXPORT_TYPE_EXPORT_CODE = 2;

    /**
     * Accrued Hours per Payroll Period calculation type.
     */
    const CALC_TYPE_ACCRUED_HOURS_PER_PAYROLL_PERIOD = 1;

    /**
     * Accrued % per Gross Hours Worked calculation type.
     */
    const CALC_TYPE_ACCRUED_PERCENTAGE_PER_GROSS_HOURS = 2;

    /**
     * Entered Manually calculation type.
     */
    const CALC_TYPE_MANUAL = 3;

    /**
     * No Accrual calculation type.
     */
    const CALC_TYPE_NO_ACCRUAL = 4;
}
