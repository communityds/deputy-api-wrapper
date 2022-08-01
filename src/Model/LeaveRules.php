<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $description
 * @property float $maxAllowedAnnually
 * @property boolean $paidLeave
 * @property boolean $annualRollOver
 * @property boolean $visible
 * @property boolean $payoutOnTermination
 * @property integer $entitlementAfterMonth
 * @property integer $exportType
 * @property string $payrollCategory
 * @property integer $calcType
 * @property string $calc
 * @property string $f00
 * @property string $f01
 * @property string $f02
 * @property string $f03
 * @property string $f04
 * @property string $f05
 * @property string $f06
 * @property string $f07
 * @property string $f08
 * @property string $f09
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
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
