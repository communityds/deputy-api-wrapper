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

To begin the OAuth2 process redirect the user to the following authentication URL:

```php
$url = $wrapper->auth->getAuthenticateUrl();
header("Location: {$url}"); // Use the appropriate method for your application
```

Once the user has accepted the authentication via Deputy the redirect URI will be hit with a `code` request parameter.

Determine the current access token by verifying the code:

```php
$code = $_GET['code']; // Use appropriate method for your application to return the `code` value
$token = $wrapper->auth->verifyCode($code);

$_SESSION['deputy_token'] = serialize($token);
```

If the token is valid it will be automatically stored in the request.
Reuse the token on the next request by persisting it somewhere, such as the session, within your application.

On the next request, retrieve the token and add it to the authentication component:

```php
$token = unserialize($_SESSION['deputy_token']);
$wrapper->auth->setAccessToken($token);
```

The authentication component will automatically refresh the token if it has expired.
As the token could automatically change it is recommended to persist the current access token when the wrapper is destroyed.

```php
$_SESSION['deputy_token'] = serialize($wrapper->auth->getAccessToken());
```

If the token is `null` then the user will need to be redirected to the authentication URL again.

To more automatically handle this process register an OAuth2 authentication handler that is called if there is no current access token.

```php
$wrapper->auth->setAuthenticateHandler(
    function () use ($wrapper) {
        $url = $wrapper->auth->getAuthenticateUrl();
        header("Location: {$url}"); // Use the appropriate method for your application
    }
);
```
