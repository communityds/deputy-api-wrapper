<?php

namespace CommunityDS\Deputy\Api;

trait WrapperLocatorTrait
{
    /**
     * Returns wrapper instance.
     *
     * @return Wrapper
     */
    protected function getWrapper()
    {
        return Wrapper::getInstance();
    }

    /**
     * Returns wrapper instance.
     *
     * @return Wrapper
     */
    protected static function getWrapperStatic()
    {
        return Wrapper::getInstance();
    }
}
