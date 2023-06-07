<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property boolean $active
 * @property string $comment
 * @property DateTime $created
 * @property integer $creator
 * @property integer $employee
 * @property DateTime $expiryDate
 * @property integer $file
 * @property DateTime $modified
 * @property integer $module
 * @property DateTime $trainingDate
 *
 * @property Employee $employeeObject
 * @property TrainingModule $moduleObject
 */
class TrainingRecord extends Record
{
}
