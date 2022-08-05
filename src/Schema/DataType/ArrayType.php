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
}
