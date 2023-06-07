# Logging

To help with debugging the behaviour of the wrapper, attach a logger to the wrapper by specifying one of the following in the configuration.

## Adapters

### Null

The default logger does nothing.

* Modify the `logger` property to contain:

```php
[
    'class' => 'CommunityDS\Deputy\Api\Adapter\Logger\NullLogger',
]
```

### PSR-3

Wraps around an existing PSR-3 compatible logger, such as those provided by a framework such as Symfony.

* Get a handle on the PSR-3 logger and modify the `logger` property to contain:

```php
[
    'class' => 'CommunityDS\Deputy\Api\Adapter\Logger\Psr3Logger',
    'logger' => $logger, // Change this to the PSR-3 handle
]
```

### Custom

Ensure the class implements the `CommunityDS\Deputy\Api\Adapter\LoggerInterface` and optionally uses the `CommunityDS\Deputy\Api\Adapter\LoggerTrait`.

This interface is a duplicate of the `psr/log` interface but allows the wrapper and a newer version of `psr/log` package to coexist.
This approach was taken to provide PHP 5.6 compatibility. 
