<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $category
 * @property string $group
 * @property integer $sortOrder
 * @property boolean $stafflog
 * @property boolean $system
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property Comment $comment
 * @property EmployeeRole $employeeRole
 *
 * @property string $displayName
 */
class Category extends Record
{

    /**
     * Returns the category name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->category;
    }
}
