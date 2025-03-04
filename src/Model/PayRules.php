<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property mixed $advancedCalculation
 * @property float $annualSalary
 * @property mixed $comment
 * @property mixed $config
 * @property string $crId
 * @property DateTime $created
 * @property integer $creator
 * @property string $ctId
 * @property integer $dexmlScript
 * @property mixed $dexmlScriptParam
 * @property float $hourlyRate
 * @property boolean $isExported
 * @property boolean $isMultiplier
 * @property string $maximumShiftLength
 * @property integer $maximumType
 * @property float $maximumValue
 * @property string $minimumShiftLength
 * @property integer $minimumType
 * @property float $minimumValue
 * @property DateTime $modified
 * @property integer $multiplierBaseRate
 * @property float $multiplierValue
 * @property integer $payPortionRule
 * @property string $payTitle
 * @property string $payrollCategory
 * @property integer $periodType
 * @property string $rateTag
 * @property integer $rateType
 * @property integer $recommendWith
 * @property integer $remunerationBy
 * @property integer $remunerationType
 * @property integer $schedule
 * @property float $unitValue
 *
 * @property PayRules $multiplierBaseRateObject
 * @property Schedule $scheduleObject
 *
 * @property CustomField $customField
 * @property OperationalUnit $operationUnit
 * @property EmploymentContract $payRules
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
