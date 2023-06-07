<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property string $category
 * @property DateTime $created
 * @property integer $creator
 * @property string $group
 * @property DateTime $modified
 * @property integer $sortOrder
 * @property boolean $stafflog
 * @property boolean $system
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
