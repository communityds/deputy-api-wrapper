<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

use CommunityDS\Deputy\Api\InvalidParamException;

/**
 * Values are UNIX timestamp integers in the API
 * but DateTime objects within PHP land.
 */
class UnixTimestamp extends DataType
{
    public function fromApi($value)
    {
        if ($value === null) {
            return null;
        }
        $dateTime = new \DateTime('now', new \DateTimeZone('UTC'));
        $dateTime->setTimestamp($value);
        return $dateTime;
    }

    public function toApi($value)
    {
        if ($value === null) {
            return null;
        }
        if ($value instanceof \DateTime) {
            $value = $value->getTimestamp();
        }
        if (!is_numeric($value)) {
            throw new InvalidParamException('Unix timestamp values must be a positive integer');
        }
        return $value;
    }

    public function phpType()
    {
        return '\DateTime';
    }
}
