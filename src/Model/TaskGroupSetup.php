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
 * @property mixed $notification
 * @property mixed $oncreate
 * @property mixed $onsubmit
 * @property mixed $plugins
 *
 * @property OperationalUnit $taskGroupOpUnit
 */
class TaskGroupSetup extends Record
{
}
