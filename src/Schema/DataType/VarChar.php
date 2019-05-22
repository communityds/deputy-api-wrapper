<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

/**
 * Values are stored as strings in API and PHP land.
 */
class VarChar extends DataType
{

    public function fromApi($value)
    {
        if ($value === null) {
            return null;
        }
        return (string) $value;
    }

    public function toApi($value)
    {
        if ($value === null) {
            return null;
        }
        return (string) $value;
    }

    public function phpType()
    {
        return 'string';
    }
}
