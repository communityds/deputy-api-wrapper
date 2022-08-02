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
}
