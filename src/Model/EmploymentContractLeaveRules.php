<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $basePayRule
 * @property integer $contractId
 * @property DateTime $created
 * @property integer $creator
 * @property integer $leaveRuleId
 * @property integer $loadingPayRule1
 * @property integer $loadingPayRule2
 * @property integer $loadingPayRule3
 * @property DateTime $modified
 *
 * @property PayRules $basePayRuleObject
 * @property EmploymentContract $contract
 * @property LeaveRules $leaveRule
 * @property PayRules $loadingPayRule1Object
 * @property PayRules $loadingPayRule2Object
 * @property PayRules $loadingPayRule3Object
 */
class EmploymentContractLeaveRules extends Record
{
}
