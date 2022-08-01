<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

use CommunityDS\Deputy\Api\InvalidParamException;

/**
 * Values are boolean within API and PHP land.
 */
class Bit extends DataType
{
    public function toApi($value)
    {
        if ($value === null || $value === false || $value === true) {
            return $value;
        }
        throw new InvalidParamException('Boolean values must be boolean or null');
    }

    public function phpType()
    {
        return 'boolean';
    }
}
