<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property mixed $comment
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $date
 * @property integer $employeeId
 * @property DateTime $modified
 *
 * @property Employee $employee
 *
 * @property Category $category
 */
class Journal extends Record
{
}
