# Deputy API Wrapper

Allows interaction with the [Deputy API (Version 1)](https://www.deputy.com/api-doc/Welcome) using an object based interface that abstracts sending and receiving content from the REST interfaces.

[View the documentation](docs/index.md) on how to use the wrapper.

## Installation

This package can be installed via Composer:

```bash
composer require communityds/deputy-api-wrapper
```

## Usage

Create a new instance of the wrapper and provide at a minimum the target and authentication configurations:

```php
use CommunityDS\Deputy\Api\Wrapper;

$wrapper = new Wrapper(
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

Then use the [helper functions](docs/resources.md) to get the records you are after.
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

More examples can be found in the [documentation](docs/index.md).

