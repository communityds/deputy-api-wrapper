<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $payTitle
 * @property integer $remunerationType
 * @property integer $remunerationBy
 * @property float $annualSalary
 * @property float $hourlyRate
 * @property boolean $isMultiplier
 * @property float $multiplierValue
 * @property integer $multiplierBaseRate
 * @property integer $minimumType
 * @property integer $maximumType
 * @property float $minimumValue
 * @property float $maximumValue
 * @property string $minimumShiftLength
 * @property string $maximumShiftLength
 * @property string $advancedCalculation
 * @property boolean $isExported
 * @property float $unitValue
 * @property integer $schedule
 * @property integer $recommendWith
 * @property integer $dexmlScript
 * @property string $dexmlScriptParam
 * @property integer $periodType
 * @property integer $payPortionRule
 * @property string $payrollCategory
 * @property string $comment
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property PayRules $multiplierBaseRateObject
 * @property Schedule $scheduleObject
 *
 * @property EmploymentContract $payRules
 * @property OperationalUnit $operationUnit
 */
class PayRules extends Record
{
    /**
     * Hourly remuneration type.
     */
    const REMUNERATION_TYPE_HOURLY = 1;

    /**
     * Unit remuneration type.
     */
    const REMUNERATION_TYPE_UNIT = 3;

    /**
     * Salary remuneration type.
     */
    const REMUNERATION_TYPE_SALARY = 2;

    /**
     * Base remuneration by.
     */
    const REMUNERATION_BY_BASE = 1;

    /**
     * Shift Loading remuneration by.
     */
    const REMUNERATION_SHIFT_LOADING = 2;

    /**
     * Period Loading remuneration by.
     */
    const REMUNERATION_PERIOD_LOADING = 3;

    /**
     * Schedule recommend with.
     */
    const RECOMMEND_WITH_SCHEDULE = 1;

    /**
     * Script recommend with.
     */
    const RECOMMEND_WITH_SCRIPT = 2;

    /**
     * Greater portion of shift pay portion rule.
     */
    const PAY_PORTION_RULE_GREATER = 1;

    /**
     * Pay exact portion pay portion rule.
     */
    const PAY_PORTION_RULE_EXACT = 2;

    /**
     * Pay full shift pay portion rule.
     */
    const PAY_PORTION_RULE_FULL = 3;
}
