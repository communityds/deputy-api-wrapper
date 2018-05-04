<?php

namespace CommunityDS\Deputy\Api\Schema;

use CommunityDS\Deputy\Api\InvalidParamException;
use Throwable;

/**
 * Indicates when a data type is not known.
 */
class UnknownDataTypeException extends InvalidParamException
{

    /**
     * Name of data type.
     *
     * @var string
     */
    public $name;

    /**
     * Resource name.
     *
     * @var string
     */
    public $resource;

    /**
     * Creates standardised exception.
     *
     * @param string $resource Resource name
     * @param string $name Data type name
     * @param integer $code Error code
     *
     * @return static
     */
    public static function create($resource, $name, $code = 0)
    {
        $e = new static('Unknown data type: ' . $resource . '::' . $name, $code);
        $e->resource = $resource;
        $e->name = $name;
        return $e;
    }
}
