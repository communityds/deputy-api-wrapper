<?php

namespace CommunityDS\Deputy\Api\Model;

/**
 * @property string $login
 * @property string $name
 * @property string $lastName
 * @property string $firstName
 * @property integer $company
 * @property string $portfolio
 * @property string $deputyVersion
 * @property integer $userId
 * @property integer $employeeId
 * @property string $primaryEmail
 * @property string $primaryPhone
 * @property string[] $permissions
 * @property string $userSince
 *
 * @property Company $companyObject
 * @property Employee $employeeObject
 * @property User $userObjectForAPI
 *
 * @property string $displayName
 */
class Me extends Record
{
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Returns the default resource configuration for this type of resource.
     *
     * @return array
     */
    public static function getResourceConfig()
    {
        return [
            'fields' => [
                'Login' => 'VarChar',
                'Name' => 'VarChar',
                'LastName' => 'VarChar',
                'FirstName' => 'VarChar',
                'Company' => 'Integer',
                'Portfolio' => 'VarChar',
                'DeputyVersion' => 'VarChar',
                'UserId' => 'Integer',
                'EmployeeId' => 'Integer',
                'PrimaryEmail' => 'VarChar',
                'PrimaryPhone' => 'VarChar',
                'Permissions' => 'VarCharArray',
                'UserSince' => 'DateTime',
            ],
            'joins' => [
                'CompanyObject' => 'Company',
                'EmployeeObject' => 'Employee',
                'UserObjectForAPI' => 'User',
            ],
        ];
    }

    /**
     * Returns null as this is virtual object.
     *
     * @return null
     */
    public function getPrimaryKey()
    {
        return null;
    }
}
