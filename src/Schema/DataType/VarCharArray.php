<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

/**
 * Values are stored as arrays of strings within API and PHP land.
 */
class VarCharArray extends DataType
{
    public function phpType()
    {
        return 'string[]';
    }
}
