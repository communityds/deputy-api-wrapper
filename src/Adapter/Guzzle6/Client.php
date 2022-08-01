<?php

namespace CommunityDS\Deputy\Api\Adapter\Guzzle6;

use CommunityDS\Deputy\Api\Adapter\ClientInterface;
use CommunityDS\Deputy\Api\Component;
use CommunityDS\Deputy\Api\Helper\ClientHelper;
use CommunityDS\Deputy\Api\Wrapper;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;

/**
 * Client adapter that wraps a Guzzle (version 6) HTTP client.
 *
 * @property HttpClient $httpClient
 */
class Client extends Component implements ClientInterface
{
    /**
     * The configuration settings for the Guzzle client.
     *
     * @var array
     *
     * @see HttpClient
     */
    public $config = [
        'curl' => [
            CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
        ],
    ];

    /**
     * Singleton instance of the Guzzle client.
     *
     * @var HttpClient
     */
    private $_httpClient = null;

    /**
     * Last error details.
     *
     * @var array
     */
    private $_lastError = null;

    /**
     * Returns the instance of the client.
     *
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if ($this->_httpClient === null) {
            $this->_httpClient = new HttpClient(
                array_merge(
                    $this->config,
                    [
                        'base_uri' => $this->getWrapper()->target->getBaseUrl(),
                    ]
                )
            );
        }
        return $this->_httpClient;
    }

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
     * Executes a request and checks the response.
     *
     * @param string $method HTTP method
     * @param string $uri URI to request
     * @param array $options Request options
     * @param integer $successCode Code that indicates a success
     *
     * @return string|array|null
     */
    public function execute($method, $uri, $options, $successCode)
    {

        $this->_lastError = null;

        $options['headers']['Content-type'] = 'application/json';
        $options['headers']['Accept'] = 'application/json';
        $options['headers']['Authorization'] = 'OAuth ' . $this->getWrapper()->auth->getToken();
        $options['headers']['dp-meta-option'] = 'none';

        try {
            $response = $this->getHttpClient()->request($method, $uri, $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $content = ClientHelper::unserialize(
                $response->getBody(true)
            );
            if ($content) {
                $this->_lastError = $content;
            }
        }

        if ($response->getStatusCode() != $successCode) {
            $content = ClientHelper::unserialize(
                $response->getBody(true)
            );
            if ($content) {
                $this->_lastError = $content;
            }
            return false;
        }

        return ClientHelper::unserialize(
            $response->getBody(true)
        );
    }

    public function get($uri, $successCode = null)
    {
        return $this->execute(
            'GET',
            $uri,
            [],
            $successCode ? $successCode : static::SUCCESS_CODE_GET
        );
    }

    public function put($uri, $payload, $successCode = null)
    {
        return $this->execute(
            'PUT',
            $uri,
            ['body' => ClientHelper::serialize($payload)],
            $successCode ? $successCode : static::SUCCESS_CODE_PUT
        );
    }

    public function post($uri, $payload, $successCode = null)
    {
        return $this->execute(
            'POST',
            $uri,
            ['body' => ClientHelper::serialize($payload)],
            $successCode ? $successCode : static::SUCCESS_CODE_POST
        );
    }

    public function delete($uri, $successCode = null)
    {
        return $this->execute(
            'DELETE',
            $uri,
            [],
            $successCode ? $successCode : static::SUCCESS_CODE_DELETE
        );
    }

    public function getLastError()
    {
        return $this->_lastError;
    }
}
