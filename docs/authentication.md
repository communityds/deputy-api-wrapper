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

Register a client by following the 'OAuth 2.0' instructions in the [Deputy API Documentation](https://www.deputy.com/api-doc/API/Authentication). 

Once you have the client details add the following as the `auth` configuration on the wrapper:

```php
'auth' => [
    'class' => 'CommunityDS\Deputy\Api\Adapter\Config\OAuth2Token',
    'clientId' => '<YOUR_CLIENT_ID_HERE>',
    'clientSecret' => '<YOUR_CLIENT_SECRET_HERE>',
    'redirectUri' => '<YOUR_CLIENT_REDIRECT_URI_HERE>',
],
```

The access tokens created during the OAuth 2.0 authentication process are managed by an access token provider that:

* Begins the OAuth 2.0 Authentication process
* Persists the current access token to storage
* Retrieves the current access token from storage

As the technical implementation of redirecting the user and persisting tokens can change based on
the framework in use, the creation of the access token provider is something up to you.

The provider must implement the `CommunityDS\Deputy\Api\Adapter\Config\OAuth2AccessTokenProviderInterface` interface.

See the `CommunityDS\Deputy\Api\Adapter\Native\OAuth2SessionAccessTokenProvider` for an example
of a simple provider but note that a custom provider should be written that conforms to the
application framework.

Register the access token provider on the `auth` configuration on the wrapper:

```php
'auth' => [
    'class' => 'CommunityDS\Deputy\Api\Adapter\Config\OAuth2Token',
    'clientId' => '<YOUR_CLIENT_ID_HERE>',
    'clientSecret' => '<YOUR_CLIENT_SECRET_HERE>',
    'redirectUri' => '<YOUR_CLIENT_REDIRECT_URI_HERE>',
    'tokenProvider' => [
        'class' => 'CommunityDS\Deputy\Api\Adapter\Native\OAuth2SessionAccessTokenProvider',
    ],
],
```
