<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

/**
 * Values are ISO-8601 combined date and time strings in the API
 * but DateTime objects within PHP land.
 */
class Time extends DateTime
{
}
