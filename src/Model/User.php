<?php

namespace CommunityDS\Deputy\Api\Model;

/**
 * @property string $displayName
 * @property integer $employee
 * @property string $photo
 */
class User extends Record
{
    /**
     * Returns the default resource configuration for this type of resource.
     *
     * @return array
     */
    public static function getResourceConfig()
    {
        return [
            'fields' => [
                'Id' => 'Integer',
                'DisplayName' => 'VarChar',
                'Employee' => 'Integer',
                'Photo' => 'VarChar',
            ],
        ];
    }
}
