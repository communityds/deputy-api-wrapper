<?php

namespace CommunityDS\Deputy\Api\Adapter;

/**
 * Interface required by any Client adapter.
 */
interface ClientInterface
{

    /**
     * Default status code to denote a successful GET request.
     */
    const SUCCESS_CODE_GET = 200;

    /**
     * Default status code that indicates a successful PUT request.
     */
    const SUCCESS_CODE_PUT = 200;

    /**
     * Default status code that indicates a successful POST request.
     */
    const SUCCESS_CODE_POST = 200;

    /**
     * Default status code that indicates a successful DELETE request.
     */
    const SUCCESS_CODE_DELETE = 200;

    /**
     * Sends a GET request to the API, checks the response code
     * and then returns the decoded response.
     *
     * @param string $uri URI relative to API root
     * @param integer $successCode HTTP code that denotes a success
     *
     * @return array|false Response body; or false on failure
     */
    public function get($uri, $successCode = null);

    /**
     * Encodes the payload, sends a PUT request to the API,
     * checks the response code then returns the decoded response.
     *
     * @param string $uri URI relative to API root
     * @param array $payload Data to send in body
     * @param integer $successCode HTTP code that denotes a success
     *
     * @return array|false Response body; or false on failure
     */
    public function put($uri, $payload, $successCode = null);

    /**
     * Encodes the payload, sends a POST request to the API,
     * checks the response code then returns the decoded response.
     *
     * @param string $uri URI relative to API root
     * @param array $payload Data to send in body
     * @param integer $successCode HTTP code that denotes a success
     *
     * @return array|false Response body; or false on failure
     */
    public function post($uri, $payload, $successCode = null);

    /**
     * Sends a DELETE request to the API, checks the response code
     * then returns the decoded response.
     *
     * @param string $uri URI relative to API root
     * @param integer $successCode HTTP code that denotes a success
     *
     * @return array|false Response body; or false on failure
     */
    public function delete($uri, $successCode = null);

    /**
     * Returns details of the last error returned by the client.
     *
     * @return array
     */
    public function getLastError();
}
