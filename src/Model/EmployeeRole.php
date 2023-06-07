<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property DateTime $created
 * @property integer $creator
 * @property DateTime $modified
 * @property mixed $permissions
 * @property integer $ranking
 * @property integer $reportTo
 * @property boolean $require2fa
 * @property string $role
 *
 * @property EmployeeRole $reportToObject
 *
 * @property EmployeeRole $alsoReportsTo
 * @property Category $employeeRole
 *
 * @property string $displayName
 * @property Memo $roleObject
 */
class EmployeeRole extends Record
{
    /**
     * Returns the role name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->role;
    }

    /**
     * Returns the `role` association as there is a name conflict
     * with the 'role' field.
     *
     * @return Memo
     */
    public function getRoleObject()
    {
        return $this->getRelation('role');
    }
}
