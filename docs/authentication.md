# Authentication

## Permanent Token

Follow the 'Permanent Token' instructions in the [Deputy API Documentation](https://www.deputy.com/api-doc/API/Authentication).

Once you have the token you need to add the following as the `auth` configuration on the wrapper:

```php
'auth' => [
    'class' => 'CommunityDS\Deputy\Api\Adapter\Config\PermanentToken',
    'token' => '<YOUR_TOKEN_HERE>',
],
```

## OAuth 2.0

Not currently supported. Ensure that the class implements the `AuthenticationInterface` interface.
If the token is not known then this should perform the OAuth authentication process.
See the 'OAuth 2.0' instructions instructions in the [Deputy API Documentation](https://www.deputy.com/api-doc/API/Authentication).
