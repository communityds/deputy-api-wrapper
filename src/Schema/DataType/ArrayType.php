<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

/**
 * Values are arrays within API and PHP land.
 */
class ArrayType extends DataType
{
    public function phpType()
    {
        return 'array';
    }

    public function fromApi($value)
    {
        // Empty arrays may come through as an empty string
        if ($value === '') {
            return [];

        // Convert non-null non-arrays to an array
        } elseif (!is_array($value) && $value !== null) {
            return [$value];
        }

        return parent::fromApi($value);
    }
}
