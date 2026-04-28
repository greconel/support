# Authentication

This API is authenticated by sending an **`Authorization`** header with the value **`"Bearer {ACCESS_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

AMPP uses an OAuth2 server implementation to authenticate our API.  
Below you will find a list of multiple ways you can implement AMPP API into your own application.
1. [Authorizing via AMPP](#authorizing-via-ampp)
2. [Password grant (e.g. for mobile applications)](#password-grant)
3. [Client credentials grant](#client-credentials-grant)
4. [Personal access token](#personal-access-token)

## Authorizing via AMPP

Using OAuth2 via authorization codes is how most developers are familiar with OAuth2.
When using authorization codes, your application will redirect a user to AMPP where they
will either approve or deny the request to issue an access token back to your application.

**To use this service, You will need a Client ID and Secret given to you by an AMPP employer.
And the users on your application should also have an account on AMPP.**

<br /><br />

### Redirecting for authorization

Once you have your client credentials, you may use your client ID and secret to request an
authorization code and access token from your application.
First, your application should make a redirect request to AMPP like so (Laravel example):

> Example request: Redirect request to AMPP

```php
Route::get('/redirect', function (Request $request) {
    $request->session()->put('state', $state = Str::random(40));

    $query = http_build_query([
        'client_id' => 'client-id',
        'redirect_uri' => 'http://redirect-uri.com/callback',
        'response_type' => 'code',
        'scope' => '',
        'state' => $state,
    ]);

    return redirect('http://passport-app.com/oauth/authorize?'.$query);
});
```

<h3>Request&nbsp;&nbsp;&nbsp;</h3>
<p>
    <small class="badge badge-green">GET</small>
    <b><code>oauth/authorize</code></b>
</p>

AMPP will automatically display a template to the user allowing them to approve or deny
the authorization request. If they approve the request, they will be redirected back to
the redirect_uri that was specified by the consuming application.
**The redirect_uri must match the redirect URL that was specified when the client was created.**

**If you would like to skip the authorization section, please contact our support.**

<br /><br />

### Converting authorization codes to access tokens

If the user approves the authorization request, they will be redirected back to your application.
You should first verify the state parameter against the value that was stored prior to the redirect.
If the state parameter matches then you should issue a POST request to AMPP to request an access token.
The request should include the authorization code that was issued by AMPP when the user approved
the authorization request.

> Example request: Converting authorization codes to access tokens

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    '/oauth/token',
    [
        'headers' => [
            'Accept' => 'application/json',
        ],
        'json' => [
            'grant_type' => 'authorization_code',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'redirect_uri' => 'http://redirect-uri',
            'code' => 'authorization-code',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```

> Example response (401):

```json
{
    "access_token": "xxx",
    "refresh_token": "xxx",
    "expires_in": "xxx"
}
```

<h3>Request&nbsp;&nbsp;&nbsp;</h3>
<p>
    <small class="badge badge-black">POST</small>
    <b><code>oauth/token</code></b>
</p>

<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>

<p>
<b><code>grant_type</code></b>&nbsp;&nbsp;<small>string</small>
<br>
The method used to get an access token.
</p>

<p>
<b><code>client_id</code></b>&nbsp;&nbsp;<small>integer</small>
<br>
The client ID.
</p>

<p>
<b><code>client_secret</code></b>&nbsp;&nbsp;<small>string</small>
<br>
The client secret associated with the client_id.
</p>

<p>
<b><code>redirect_uri</code></b>&nbsp;&nbsp;<small>string</small>
<br>
The redirect url of your application.
</p>

<p>
<b><code>code</code></b>&nbsp;&nbsp;<small>string</small>
<br>
The authorization code.
</p>

## Password grant

This method allows your application, such as a mobile application, to obtain an access token
using an email address / username and password. This allows AMPP to issue access tokens securely
to your application without requiring your users to go through the entire OAuth2 authorization code
redirect flow.

**This method is for AMPP developers only, but we list it here so you can see we have multiple solutions.**

## Client credentials grant

The client credentials grant is suitable for machine-to-machine authentication.
For example, we might use this grant in a scheduled job which is performing maintenance
tasks over an API. 

**To use this service, You will need a Client ID and Secret given to you by an AMPP employer.**

<br /><br />

### Retrieving Tokens

To retrieve a token using this grant type, make a POST request to the oauth/token endpoint.

> Example request

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    '/oauth/token',
    [
        'headers' => [
            'Accept' => 'application/json',
        ],
        'json' => [
            'grant_type' => 'client_credentials',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'scope' => '',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```

> Example response (401):

```json
{
    "access_token": "xxx",
    "refresh_token": "xxx",
    "expires_in": "xxx"
}
```

<h3>Request&nbsp;&nbsp;&nbsp;</h3>
<p>
    <small class="badge badge-black">POST</small>
    <b><code>oauth/token</code></b>
</p>

<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>

<p>
<b><code>grant_type</code></b>&nbsp;&nbsp;<small>string</small>
<br>
The method used to get an access token.
</p>

<p>
<b><code>client_id</code></b>&nbsp;&nbsp;<small>integer</small>
<br>
The client ID.
</p>

<p>
<b><code>client_secret</code></b>&nbsp;&nbsp;<small>string</small>
<br>
The client secret associated with the client_id.
</p>

<p>
<b><code>scope</code></b>&nbsp;&nbsp;<small>string</small>
<br>
The scopes to request.
</p>

## Personal access token

Sometimes, users may want to issue access tokens to themselves without going through the
typical authorization code redirect flow. Maybe for experimental usage or personal projects.

To manage your personal access tokens, you can go to your profile page under the "Tokens" tab.

**Never share your access token or your data on AMPP might be vulnerable!**

## Refreshing tokens

You can refresh your access tokens when necessary.

> Example request: Refreshing tokens

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    '/oauth/token',
    [
        'headers' => [
            'Accept' => 'application/json',
        ],
        'json' => [
            'grant_type' => 'refresh_token',
            'refresh_token' => 'the-refresh-token',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'scope' => '',
        ],
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```

> Example response (401):

```json
{
    "access_token": "xxx",
    "refresh_token": "xxx",
    "expires_in": "xxx"
}
```

<h3>Request&nbsp;&nbsp;&nbsp;</h3>
<p>
    <small class="badge badge-black">POST</small>
    <b><code>oauth/token</code></b>
</p>

<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>

<p>
<b><code>grant_type</code></b>&nbsp;&nbsp;<small>string</small>
<br>
The method used to get an access token.
</p>

<p>
<b><code>refresh_token</code></b>&nbsp;&nbsp;<small>string</small>
<br>
The refresh token.
</p>

<p>
<b><code>client_id</code></b>&nbsp;&nbsp;<small>integer</small>
<br>
The client ID.
</p>

<p>
<b><code>client_secret</code></b>&nbsp;&nbsp;<small>string</small>
<br>
The client secret associated with the client_id.
</p>

<p>
<b><code>scope</code></b>&nbsp;&nbsp;<small>string</small>
<br>
The scopes to request.
</p>

## Revoke tokens

You can revoke your access tokens when necessary.

> Example request: Refreshing tokens

```php

$client = new \GuzzleHttp\Client();
$response = $client->get(
    '/api/revoke-token',
    [
        'headers' => [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer {ACCESS_TOKEN}',
        ]
    ]
);
$body = $response->getBody();
print_r(json_decode((string) $body));
```

<h3>Request&nbsp;&nbsp;&nbsp;</h3>
<p>
    <small class="badge badge-green">GET</small>
    <b><code>api/revoke-token</code></b>
</p>
