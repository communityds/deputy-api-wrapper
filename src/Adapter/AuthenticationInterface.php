<?php

namespace CommunityDS\Deputy\Api\Adapter;

interface AuthenticationInterface
{

    /**
     * Returns the token used for identification with each request.
     *
     * @return string
     */
    public function getToken();
}
