<?php

namespace CommunityDS\Deputy\Api;

use CommunityDS\Deputy\Api\Adapter\LoggerInterface;

/**
 * Provides access to the logger.
 */
trait LoggerLocatorTrait
{
    use WrapperLocatorTrait;

    /**
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        return $this->getWrapper()->logger;
    }
}
