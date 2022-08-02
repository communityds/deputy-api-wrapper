<?php

namespace CommunityDS\Deputy\Api\Model;

use DateTime;

/**
 * @property integer $id
 * @property string $role
 * @property integer $ranking
 * @property integer $reportTo
 * @property string $permissions
 * @property boolean $require2fa
 * @property integer $creator
 * @property DateTime $created
 * @property DateTime $modified
 *
 * @property EmployeeRole $reportToObject
 *
 * @property Category $employeeRole
 * @property EmployeeRole $alsoReportsTo
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
