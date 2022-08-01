<?php

namespace CommunityDS\Deputy\Api\Tests\Adapter\Guzzle6;

use CommunityDS\Deputy\Api\Tests\Adapter\AdapterTestCase;

class ClientTest extends AdapterTestCase
{
    protected function getClientConfig()
    {
        return [
            'class' => 'CommunityDS\Deputy\Api\Adapter\Guzzle6\Client',
        ];
    }
}
