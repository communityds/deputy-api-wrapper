<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $key
 * @property string $name
 * @property boolean $active
 * @property boolean $compulsory
 * @property string $notification
 * @property float $deadline
 * @property string $plugins
 * @property string $oncreate
 * @property string $onsubmit
 * @property string $comment
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property OperationalUnit $taskGroupOpUnit
 */
class TaskGroupSetup extends Record
{
}
