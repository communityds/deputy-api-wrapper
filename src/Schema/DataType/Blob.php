<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

/**
 * Values are mixed within PHP land but string when sent to the API.
 */
class Blob extends VarChar
{
    public function fromApi($value)
    {
        return $value;
    }

    public function phpType()
    {
        return 'mixed';
    }
}
