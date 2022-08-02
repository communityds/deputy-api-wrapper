<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $topic
 * @property string $filters
 * @property string $address
 * @property string $type
 * @property string $headers
 * @property boolean $enabled
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 */
class Webhook extends Record
{
}
