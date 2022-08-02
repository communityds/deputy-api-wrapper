<?php

namespace CommunityDS\Deputy\Api\Schema;

use CommunityDS\Deputy\Api\InvalidParamException;
use Throwable;

/**
 * Indicates that a specific field is not known.
 */
class UnknownFieldException extends InvalidParamException
{
    /**
     * Name of field.
     *
     * @var string
     */
    public $name;

    /**
     * Model containing field.
     *
     * @var object
     */
    public $instance;

    /**
     * Creates standardised exception.
     *
     * @param object $instance Object instance
     * @param string $name Field name
     * @param integer $code Error code
     *
     * @return static
     */
    public static function create($instance, $name, $code = 0)
    {
        $e = new static('Unknown field: ' . $name, $code);
        $e->instance = $instance;
        $e->name = $name;
        return $e;
    }
}
