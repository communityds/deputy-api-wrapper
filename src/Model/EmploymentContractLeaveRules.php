<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $contractId
 * @property integer $leaveRuleId
 * @property integer $basePayRule
 * @property integer $loadingPayRule1
 * @property integer $loadingPayRule2
 * @property integer $loadingPayRule3
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property EmploymentContract $contract
 * @property LeaveRules $leaveRule
 * @property PayRules $basePayRuleObject
 * @property PayRules $loadingPayRule1Object
 * @property PayRules $loadingPayRule2Object
 * @property PayRules $loadingPayRule3Object
 */
class EmploymentContractLeaveRules extends Record
{
}
