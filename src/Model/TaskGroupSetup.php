<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $active
 * @property string $comment
 * @property boolean $compulsory
 * @property DateTime $created
 * @property integer $creator
 * @property float $deadline
 * @property string $key
 * @property boolean $laborModel
 * @property DateTime $modified
 * @property string $name
 * @property string $notification
 * @property string $oncreate
 * @property string $onsubmit
 * @property string $plugins
 *
 * @property OperationalUnit $taskGroupOpUnit
 */
class TaskGroupSetup extends Record
{
}
