<?php

namespace CommunityDS\Deputy\Api\Schema;

use CommunityDS\Deputy\Api\InvalidParamException;
use Throwable;

/**
 * Indicates that a specific relationship is not known.
 */
class UnknownRelationException extends InvalidParamException
{
    /**
     * Name of relation.
     *
     * @var string
     */
    public $name;

    /**
     * Model containing relation.
     *
     * @var object
     */
    public $instance;

    /**
     * Creates standardised exception.
     *
     * @param object $instance Object instance
     * @param string $name Relation name
     * @param integer $code Error code
     *
     * @return static
     */
    public static function create($instance, $name, $code = 0)
    {
        $e = new static('Unknown relationship: ' . $name, $code);
        $e->instance = $instance;
        $e->name = $name;
        return $e;
    }
}
