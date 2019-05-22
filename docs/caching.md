# Caching

## Run time

Cached values only exist within the currently running process.
The next time a new process starts the cache will be cleared.

This is beneficial for reducing the number of calls to the API to retrieve resources.

Access this via the `runtime` property on the Wrapper:

```php
$user = $wrapper->runtime->get('current_user', null);
if ($user === null) {
    $me = $wrapper->getMe();
    $user = $wrapper->getUser($me->userId);
    $wrapper->runtime->set('current_user', $user);
}
```

Configured appropriately:

```php
[
    'class' => 'CommunityDS\Deputy\Api\Native\RuntimeCache',
]
```

## Persistent

Cached values exist between running processes as it stores values to disk.

This is beneficial for reducing the number of calls to the API to retrieve schema information or consistent data.

Access this via the `persistent` property on the Wrapper:

```php
$company = $wrapper->persistent->get('our_company', null);
if ($company === null) {
    $company = $wrapper->getCompany(3);
    $wrapper->persistent->set('our_company', $company);
}
```

### File Cache

Stores the persistent cache to a specific file within the file system.
Configured appropriately:

```php
[
    'class' => 'CommunityDS\Deputy\Api\Native\FileCache',
    'file' => '<PATH_TO_WRITEABLE FILE>',
]
```

## Extending

Additional caches can be added here so long as the class implements the `CacheInterface` interface.
For example you may want to use an existing cache within your framework of choice.
Check the `Adapter` folder to see if the wrapper has already been created.