<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property integer $employee
 * @property integer $module
 * @property DateTime $trainingDate
 * @property DateTime $expiryDate
 * @property boolean $active
 * @property string $comment
 * @property integer $file
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Employee $employeeObject
 * @property TrainingModule $moduleObject
 */
class TrainingRecord extends Record
{
}
