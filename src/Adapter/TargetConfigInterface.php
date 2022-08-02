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

    /**
     * Returns the domain of the OAuth endpoint.
     *
     * @return string
     */
    public function getOAuth2EndPoint();

    /**
     * Returns the OAuth access token endpoint to rewew a token.
     *
     * @return string
     */
    public function getOAuth2AccessTokenUrl();
}
