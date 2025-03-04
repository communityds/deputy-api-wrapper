<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

/**
 * Values are stored as strings in API and PHP land.
 */
class Json extends DataType
{
    public function fromApi($value)
    {
        if ($value === null) {
            return null;
        }
        return json_decode($value, true);
    }

    public function toApi($value)
    {
        if ($value === null) {
            return null;
        }
        return json_encode($value);
    }

    public function phpType()
    {
        return 'mixed';
    }
}
