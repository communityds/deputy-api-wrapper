<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

/**
 * Values are stored as integers or null values in both API and PHP land.
 */
class Integer extends DataType
{
    public function fromApi($value)
    {
        if ($value === null) {
            return null;
        }
        return (int) $value;
    }

    public function toApi($value)
    {
        if ($value === null) {
            return $value;
        }
        return (int) $value;
    }

    public function phpType()
    {
        return 'integer';
    }
}
