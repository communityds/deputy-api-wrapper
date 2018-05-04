<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

/**
 * Values are midnight ISO-8601 combined date and time strings in the API
 * but DateTime objects within PHP land.
 */
class Date extends DateTime
{

    public function phpType()
    {
        return '\DateTime';
    }
}
