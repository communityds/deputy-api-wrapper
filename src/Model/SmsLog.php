<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $destination
 * @property string $message
 * @property integer $count
 * @property string $smsId
 * @property string $deliveryReport
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 */
class SmsLog extends Record
{
}
