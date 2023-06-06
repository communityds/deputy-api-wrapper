<?php

namespace CommunityDS\Deputy\Api\Schema\DataType;

use CommunityDS\Deputy\Api\Component;
use CommunityDS\Deputy\Api\Schema\DataTypeInterface;

/**
 * Foundation for any data types.
 */
abstract class DataType extends Component implements DataTypeInterface
{
    public function fromApi($value)
    {
        return $value;
    }

    public function toApi($value)
    {
        return $value;
    }
}
