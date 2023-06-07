# HTTP Clients

HTTP Clients are classes that wrap the communication to the Deputy API.
The clients can use any underlying technology to do so but must implement the `ClientInterface` interface.

By default, the wrapper uses the `Guzzle` HTTP wrapper which by default uses `curl`.

## Adapters

### Guzzle 7

> NOTE: This version of the adapter applies to PHP 8.0+ environments.

The Guzzle adapter uses the Guzzle HTTP library loaded via Composer.
The adapter uses the default configuration of Guzzle so in effect acts as a `curl` wrapper.

If you wish to configure the Guzzle wrapper then you need to:

* Add Guzzle to your composer requirements:

```bash
composer require guzzlehttp/guzzle ^7.0
```

* Modify the `client` property to contain:

```php
[
    'class' => 'CommunityDS\Deputy\Api\Adapter\Guzzle7\Client',
]
```

* A more advanced example is:

```php
[
    'class' => 'CommunityDS\Deputy\Api\Adapter\Guzzle7\Client',
    'config' => [
        'verify' => false,
    ],
    'options' => [
        'curl' => [
            CURLOPT_SSLVERSION => 6,
            CURLOPT_PROXY => '192.168.0.1',
            CURLOPT_PROXYPORT => '8080'
        ],
    ],
]
```

### Guzzle 6

> NOTE: This version of the adapter applies to PHP 5.5+ environments.

The Guzzle adapter uses the Guzzle HTTP library loaded via Composer.
The adapter uses the default configuration of Guzzle so in effect acts as a `curl` wrapper.

If you wish to configure the Guzzle wrapper then you need to:

* Add Guzzle to your composer requirements:

```bash
composer require guzzlehttp/guzzle ^6.0
```

* Modify the `client` property to contain:

```php
[
    'class' => 'CommunityDS\Deputy\Api\Adapter\Guzzle6\Client',
]
```

* A more advanced example is:

```php
[
    'class' => 'CommunityDS\Deputy\Api\Adapter\Guzzle6\Client',
    'config' => [
        'verify' => false,
    ],
    'options' => [
        'curl' => [
            CURLOPT_SSLVERSION => 6,
            CURLOPT_PROXY => '192.168.0.1',
            CURLOPT_PROXYPORT => '8080'
        ],
    ],
]
```

See the Guzzle HTTP documentation for more examples on how the client can be configured.

Note that due to issues with `curl` you may need to specify the ssl version that is used by the Deputy API.

### Custom

Ensure the client class extends the `Component` class and implements the `ClientInterface` class.

By default the success codes are passed in as `null`.
Convert `null` to the relevant constant within the `ClientInterface` interface.

The following headers should be set for each connection:

```php
[
    'Content-type' => 'application/json',
    'Accept' => 'application/json',
    'Authorization' => 'OAuth ' . Wrapper::getInstance()->auth->getToken(),
    'dp-meta-option' => 'none',
]
```

When an error occurs, an exception is thrown or unexpected response code it is expected that the client will handle these and return `false`.
The error information should be stored that can then be retrieved by the `getLastError` function.