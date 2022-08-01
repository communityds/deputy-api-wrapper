<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

/**
 * Values are string within API and PHP land.
 */
class Blob extends VarChar
{
    public function phpType()
    {
        return 'string';
    }
}
