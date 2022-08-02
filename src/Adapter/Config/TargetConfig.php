<?php

namespace CommunityDS\Deputy\Api\Adapter\Config;

use CommunityDS\Deputy\Api\Adapter\TargetConfigInterface;
use CommunityDS\Deputy\Api\Component;

/**
 * Stores configuration regarding the targeted Deputy API.
 */
class TargetConfig extends Component implements TargetConfigInterface
{
    /**
     * Domain name of endpoint.
     *
     * @var string
     */
    public $domain;

    public function getBaseUrl()
    {
        return 'https://' . $this->domain . '/api/v1/';
    }

    public function getOAuth2EndPoint()
    {
        return $this->domain;
    }

    public function getOAuth2AccessTokenUrl()
    {
        return 'https://' . $this->domain . '/oauth/access_token';
    }
}
