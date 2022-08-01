<?php

namespace CommunityDS\Deputy\Api\Adapter;

/**
 * Interface required by any adapter used to configure the
 * details of the API.
 */
interface TargetConfigInterface
{
    /**
     * Returns the base url of the REST api.
     *
     * @return string
     */
    public function getBaseUrl();
}
