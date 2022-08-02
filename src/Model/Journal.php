<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property DateTime $date
 * @property integer $employeeId
 * @property string $comment
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Employee $employee
 *
 * @property Category $category
 */
class Journal extends Record
{
}
