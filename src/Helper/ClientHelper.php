<?php

namespace CommunityDS\Deputy\Api\Helper;

use CommunityDS\Deputy\Api\DeputyException;

/**
 * Helper functions for HTTP client adapters.
 *
 * @see \CommunityDS\Deputy\Api\Adapter\ClientInterface
 */
abstract class ClientHelper
{
    /**
     * Converts payload to JSON string.
     *
     * @param array $payload Payload to convert
     *
     * @return string
     */
    public static function serialize($payload)
    {
        return json_encode($payload);
    }

    /**
     * Converts response to array structure.
     *
     * @param string $body Body to unserialize
     *
     * @return array
     */
    public static function unserialize($body)
    {
        return json_decode($body, true);
    }

    /**
     * Detects for error details within the unserialized response.
     *
     * @param array $response Unserialized response content
     *
     * @return void
     *
     * @throws DeputyException When the response contains an error
     */
    public static function checkResponse($response)
    {
        if (is_array($response)) {
            if (isset($response['error'])) {
                throw new DeputyException(
                    $response['error']['message'],
                    $response['error']['code']
                );
            }
        }
    }
}
