<?php

namespace CommunityDS\Deputy\Api\Adapter\Guzzle3;

use CommunityDS\Deputy\Api\Adapter\ClientInterface;
use CommunityDS\Deputy\Api\Component;
use CommunityDS\Deputy\Api\Helper\ClientHelper;
use CommunityDS\Deputy\Api\Wrapper;
use Guzzle\Http\Client as HttpClient;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Message\RequestInterface;

/**
 * Client adapter that wraps a Guzzle HTTP client.
 *
 * @property HttpClient $httpClient
 */
class Client extends Component implements ClientInterface
{

    /**
     * The configuration options for the Guzzle client.
     *
     * @var array
     *
     * @see HttpClient
     */
    public $config = [
        'curl.options' => [
            CURLOPT_SSLVERSION => 6, // CURL_SSLVERSION_TLSv1_2 (constant not defined within PHP 5.3)
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
                $this->getWrapper()->target->getBaseUrl(),
                $this->config
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
     * @param RequestInterface $request Request instance
     * @param integer $successCode Code that indicates a success
     *
     * @return string|array|null
     */
    public function execute($request, $successCode)
    {

        $this->_lastError = null;

        $request->setHeader('Content-type', 'application/json');
        $request->setHeader('Accept', 'application/json');
        $request->setHeader('Authorization', 'OAuth ' . $this->getWrapper()->auth->getToken());
        $request->setHeader('dp-meta-option', 'none');

        try {
            $response = $request->send();
        } catch (BadResponseException $e) {
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
            $this->getHttpClient()->get(
                $uri
            ),
            $successCode ? $successCode : static::SUCCESS_CODE_GET
        );
    }

    public function put($uri, $payload, $successCode = null)
    {
        return $this->execute(
            $this->getHttpClient()->put(
                $uri,
                null,
                ClientHelper::serialize($payload)
            ),
            $successCode ? $successCode : static::SUCCESS_CODE_PUT
        );
    }

    public function post($uri, $payload, $successCode = null)
    {
        return $this->execute(
            $this->getHttpClient()->post(
                $uri,
                null,
                ClientHelper::serialize($payload)
            ),
            $successCode ? $successCode : static::SUCCESS_CODE_POST
        );
    }

    public function delete($uri, $successCode = null)
    {
        return $this->execute(
            $this->getHttpClient()->delete(
                $uri
            ),
            $successCode ? $successCode : static::SUCCESS_CODE_DELETE
        );
    }

    public function getLastError()
    {
        return $this->_lastError;
    }
}
