<?php

namespace CommunityDS\Deputy\Api\Schema;

/**
 * Represents a type of data that the Deputy API can support.
 */
interface DataTypeInterface
{

    /**
     * Converts a value from the API to the PHP value.
     *
     * @param mixed $value Value from API
     *
     * @return mixed PHP value
     */
    public function fromApi($value);

    /**
     * Converts a value from PHP to the API value.
     *
     * @param mixed $value Value from PHP
     *
     * @return mixed API value
     */
    public function toApi($value);
}
