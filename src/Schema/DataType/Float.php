<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

/**
 * Values are stored as floats or null values in both API and PHP land.
 */
class Float extends DataType
{

    public function fromApi($value)
    {
        if ($value === null) {
            return null;
        }
        return (float) $value;
    }

    public function toApi($value)
    {
        if ($value === null) {
            return $value;
        }
        return (float) $value;
    }

    public function phpType()
    {
        return 'float';
    }
}
