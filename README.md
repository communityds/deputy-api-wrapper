# Deputy API Wrapper

[![Latest Stable Version](https://img.shields.io/packagist/v/communityds/deputy-api-wrapper.svg)](https://packagist.org/packages/communityds/deputy-api-wrapper)
[![Total Downloads](https://img.shields.io/packagist/dt/communityds/deputy-api-wrapper.svg)](https://packagist.org/packages/communityds/deputy-api-wrapper)
[![Build Status](https://img.shields.io/travis/communityds/deputy-api-wrapper.svg)](https://travis-ci.org/communityds/deputy-api-wrapper)
[![License](https://img.shields.io/github/license/communityds/deputy-api-wrapper.svg)](LICENSE)

Allows interaction with the [Deputy API (Version 1)](https://www.deputy.com/api-doc/Welcome) using an object based interface that abstracts sending and receiving content from the REST API.

[View the documentation](docs/index.md) on how to use the wrapper.

## Installation

This package can be installed via Composer:

```bash
composer require communityds/deputy-api-wrapper
```

By default, this package uses the Guzzle library to send the API requests.
Install the package via Composer:

```bash
composer require guzzlehttp/guzzle ^6.0
```

See the [HTTP Clients documentation](docs/http_clients.md) to see what other libraries can be used instead.

## Usage

Create a singleton instance of the wrapper and provide at a minimum the authentication and target component configurations:

```php
use CommunityDS\Deputy\Api\Wrapper;

$wrapper = Wrapper::setInstance(
    [
        'auth' => [
            'class' => 'CommunityDS\Deputy\Api\Adapter\Config\PermanentToken',
            'token' => '<YOUR_OAUTH_TOKEN_HERE>',
        ],
        'target' => [
            'class' => 'CommunityDS\Deputy\Api\Adapter\Config\TargetConfig',
            'domain' => '<YOUR_DOMAIN_HERE>',
        ],
    ]
);
```

Use the [helper functions](docs/resources.md) to get the records you are after.
The example below returns all of today's schedules/rosters:

```php
$today = mktime(0, 0, 0);
$shifts = $wrapper->findRosters()
    ->andWhere(['>=', 'startTime', $today])
    ->andWhere(['<', 'endTime', strtotime('+1 day', $today)])
    ->joinWith('employeeObject')
    ->joinWith('operationalUnitObject')
    ->all();
foreach ($shifts as $shift) {
    echo date('h:ia', $shift->startTime)
        . ' to ' . date('h:ia', $shift->endTime)
        . ' for ' . $shift->employeeObject->displayName
        . ' at ' . $shift->operationalUnitObject->displayName
        . PHP_EOL;
}
```

More details and examples can be found in the [documentation](docs/index.md).

