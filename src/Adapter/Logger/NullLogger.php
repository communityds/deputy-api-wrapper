<?php

namespace CommunityDS\Deputy\Api\Adapter\Logger;

use CommunityDS\Deputy\Api\Adapter\LoggerInterface;
use CommunityDS\Deputy\Api\Adapter\LoggerTrait;
use CommunityDS\Deputy\Api\Component;

/**
 * Does not log any messages.
 */
class NullLogger extends Component implements LoggerInterface
{
    use LoggerTrait;

    public function log($level, $message, array $context)
    {
        // Do nothing
    }
}
