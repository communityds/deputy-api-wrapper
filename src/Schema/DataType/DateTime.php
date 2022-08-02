<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

use CommunityDS\Deputy\Api\InvalidParamException;

/**
 * Values are ISO-8601 combined date and time strings in the API
 * but DateTime objects within PHP land.
 */
class DateTime extends DataType
{
    public function fromApi($value)
    {
        if ($value === null) {
            return null;
        }
        return new \DateTime($value);
    }

    public function toApi($value)
    {
        if ($value === null) {
            return null;
        }
        if (is_string($value)) {
            $value = new \DateTime($value);
        }
        if (!($value instanceof \DateTime)) {
            throw new InvalidParamException('Date time values must be DateTime instances');
        }
        return $value->format(\DateTime::ATOM);
    }

    public function phpType()
    {
        return '\DateTime';
    }
}
