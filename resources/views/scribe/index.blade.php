<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>AMPP API documentation</title>

    <link href="https://fonts.googleapis.com/css?family=PT+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@10.7.2/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@10.7.2/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .php-example code { display: none; }
                    body .content .bash-example code { display: none; }
                    body .content .javascript-example code { display: none; }
            </style>

    <script>
        var baseUrl = "http://ampp.dev.test";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("vendor/scribe/js/tryitout-3.24.0.js") }}"></script>

    <script src="{{ asset("vendor/scribe/js/theme-default-3.24.0.js") }}"></script>

</head>

<body data-languages="[&quot;php&quot;,&quot;bash&quot;,&quot;javascript&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("vendor/scribe/images/navbar.png") }}" alt="navbar-image" />
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="php">php</button>
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                            <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                                                                            <ul id="tocify-header-0" class="tocify-header">
                    <li class="tocify-item level-1" data-unique="introduction">
                        <a href="#introduction">Introduction</a>
                    </li>
                                            
                                                                    </ul>
                                                <ul id="tocify-header-1" class="tocify-header">
                    <li class="tocify-item level-1" data-unique="authentication">
                        <a href="#authentication">Authentication</a>
                    </li>
                                            
                                                                    <ul id="tocify-subheader-authentication" class="tocify-subheader">
                                                        <li class="tocify-item level-2"
                        data-unique="authentication-authorizing-via-ampp">
                        <a href="#authorizing-via-ampp">Authorizing via AMPP</a>
                    </li>
            
                                
                                
                                                                    <li class="tocify-item level-2"
                        data-unique="authentication-password-grant">
                        <a href="#password-grant">Password grant</a>
                    </li>
            
                                                                    <li class="tocify-item level-2"
                        data-unique="authentication-client-credentials-grant">
                        <a href="#client-credentials-grant">Client credentials grant</a>
                    </li>
            
                                
                                                                    <li class="tocify-item level-2"
                        data-unique="authentication-personal-access-token">
                        <a href="#personal-access-token">Personal access token</a>
                    </li>
            
                                                                    <li class="tocify-item level-2"
                        data-unique="authentication-refreshing-tokens">
                        <a href="#refreshing-tokens">Refreshing tokens</a>
                    </li>
            
                                                                    <li class="tocify-item level-2"
                        data-unique="authentication-revoke-tokens">
                        <a href="#revoke-tokens">Revoke tokens</a>
                    </li>
            
                                                    </ul>
                                    </ul>
                    
                    <ul id="tocify-header-2" class="tocify-header">
                <li class="tocify-item level-1" data-unique="clients">
                    <a href="#clients">Clients</a>
                </li>
                                    <ul id="tocify-subheader-clients" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="clients-POSTapi-v1-clients-create">
                        <a href="#clients-POSTapi-v1-clients-create">Create a new client</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="clients-GETapi-v1-clients">
                        <a href="#clients-GETapi-v1-clients">Get all clients</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="clients-PATCHapi-v1-clients-edit">
                        <a href="#clients-PATCHapi-v1-clients-edit">Update an existing client</a>
                    </li>
                                                    </ul>
                            </ul>
                    <ul id="tocify-header-3" class="tocify-header">
                <li class="tocify-item level-1" data-unique="invoices">
                    <a href="#invoices">Invoices</a>
                </li>
                                    <ul id="tocify-subheader-invoices" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="invoices-GETapi-v1-invoices">
                        <a href="#invoices-GETapi-v1-invoices">Get all invoices for a client</a>
                    </li>
                                    <li class="tocify-item level-2" data-unique="invoices-POSTapi-v1-invoices-create">
                        <a href="#invoices-POSTapi-v1-invoices-create">Create a new invoice</a>
                    </li>
                                                    </ul>
                            </ul>
        
                        
            </div>

            <ul class="toc-footer" id="toc-footer">
                            <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
                    </ul>
        <ul class="toc-footer" id="last-updated">
        <li>Last updated: June 22 2022</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>This documentation aims to provide all the information you need to work with our API.</p>
<aside>As you scroll, you'll see code examples for working with the API in different programming languages in the dark area to the right (or as part of the content on mobile).
You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).</aside>
<blockquote>
<p>Base URL</p>
</blockquote>
<pre><code class="language-yaml">https://www.ampp.dev</code></pre>

        <h1 id="authentication">Authentication</h1>
<p>This API is authenticated by sending an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {ACCESS_TOKEN}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>AMPP uses an OAuth2 server implementation to authenticate our API.<br />
Below you will find a list of multiple ways you can implement AMPP API into your own application.</p>
<ol>
<li><a href="#authorizing-via-ampp">Authorizing via AMPP</a></li>
<li><a href="#password-grant">Password grant (e.g. for mobile applications)</a></li>
<li><a href="#client-credentials-grant">Client credentials grant</a></li>
<li><a href="#personal-access-token">Personal access token</a></li>
</ol>
<h2 id="authorizing-via-ampp">Authorizing via AMPP</h2>
<p>Using OAuth2 via authorization codes is how most developers are familiar with OAuth2.
When using authorization codes, your application will redirect a user to AMPP where they
will either approve or deny the request to issue an access token back to your application.</p>
<p><strong>To use this service, You will need a Client ID and Secret given to you by an AMPP employer.
And the users on your application should also have an account on AMPP.</strong></p>
<p><br /><br /></p>
<h3 id="redirecting-for-authorization">Redirecting for authorization</h3>
<p>Once you have your client credentials, you may use your client ID and secret to request an
authorization code and access token from your application.
First, your application should make a redirect request to AMPP like so (Laravel example):</p>
<blockquote>
<p>Example request: Redirect request to AMPP</p>
</blockquote>
<pre><code class="language-php">Route::get('/redirect', function (Request $request) {
    $request-&gt;session()-&gt;put('state', $state = Str::random(40));

    $query = http_build_query([
        'client_id' =&gt; 'client-id',
        'redirect_uri' =&gt; 'http://redirect-uri.com/callback',
        'response_type' =&gt; 'code',
        'scope' =&gt; '',
        'state' =&gt; $state,
    ]);

    return redirect('http://passport-app.com/oauth/authorize?'.$query);
});</code></pre>
<h3>Request&nbsp;&nbsp;&nbsp;</h3>
<p>
    <small class="badge badge-green">GET</small>
    <b><code>oauth/authorize</code></b>
</p>
<p>AMPP will automatically display a template to the user allowing them to approve or deny
the authorization request. If they approve the request, they will be redirected back to
the redirect_uri that was specified by the consuming application.
<strong>The redirect_uri must match the redirect URL that was specified when the client was created.</strong></p>
<p><strong>If you would like to skip the authorization section, please contact our support.</strong></p>
<p><br /><br /></p>
<h3 id="converting-authorization-codes-to-access-tokens">Converting authorization codes to access tokens</h3>
<p>If the user approves the authorization request, they will be redirected back to your application.
You should first verify the state parameter against the value that was stored prior to the redirect.
If the state parameter matches then you should issue a POST request to AMPP to request an access token.
The request should include the authorization code that was issued by AMPP when the user approved
the authorization request.</p>
<blockquote>
<p>Example request: Converting authorization codes to access tokens</p>
</blockquote>
<pre><code class="language-php">
$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    '/oauth/token',
    [
        'headers' =&gt; [
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'grant_type' =&gt; 'authorization_code',
            'client_id' =&gt; 'client-id',
            'client_secret' =&gt; 'client-secret',
            'redirect_uri' =&gt; 'http://redirect-uri',
            'code' =&gt; 'authorization-code',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "access_token": "xxx",
    "refresh_token": "xxx",
    "expires_in": "xxx"
}</code></pre>
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
<h2 id="password-grant">Password grant</h2>
<p>This method allows your application, such as a mobile application, to obtain an access token
using an email address / username and password. This allows AMPP to issue access tokens securely
to your application without requiring your users to go through the entire OAuth2 authorization code
redirect flow.</p>
<p><strong>This method is for AMPP developers only, but we list it here so you can see we have multiple solutions.</strong></p>
<h2 id="client-credentials-grant">Client credentials grant</h2>
<p>The client credentials grant is suitable for machine-to-machine authentication.
For example, we might use this grant in a scheduled job which is performing maintenance
tasks over an API. </p>
<p><strong>To use this service, You will need a Client ID and Secret given to you by an AMPP employer.</strong></p>
<p><br /><br /></p>
<h3 id="retrieving-tokens">Retrieving Tokens</h3>
<p>To retrieve a token using this grant type, make a POST request to the oauth/token endpoint.</p>
<blockquote>
<p>Example request</p>
</blockquote>
<pre><code class="language-php">
$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    '/oauth/token',
    [
        'headers' =&gt; [
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'grant_type' =&gt; 'client_credentials',
            'client_id' =&gt; 'client-id',
            'client_secret' =&gt; 'client-secret',
            'scope' =&gt; '',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "access_token": "xxx",
    "refresh_token": "xxx",
    "expires_in": "xxx"
}</code></pre>
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
<h2 id="personal-access-token">Personal access token</h2>
<p>Sometimes, users may want to issue access tokens to themselves without going through the
typical authorization code redirect flow. Maybe for experimental usage or personal projects.</p>
<p>To manage your personal access tokens, you can go to your profile page under the &quot;Tokens&quot; tab.</p>
<p><strong>Never share your access token or your data on AMPP might be vulnerable!</strong></p>
<h2 id="refreshing-tokens">Refreshing tokens</h2>
<p>You can refresh your access tokens when necessary.</p>
<blockquote>
<p>Example request: Refreshing tokens</p>
</blockquote>
<pre><code class="language-php">
$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    '/oauth/token',
    [
        'headers' =&gt; [
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'grant_type' =&gt; 'refresh_token',
            'refresh_token' =&gt; 'the-refresh-token',
            'client_id' =&gt; 'client-id',
            'client_secret' =&gt; 'client-secret',
            'scope' =&gt; '',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "access_token": "xxx",
    "refresh_token": "xxx",
    "expires_in": "xxx"
}</code></pre>
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
<h2 id="revoke-tokens">Revoke tokens</h2>
<p>You can revoke your access tokens when necessary.</p>
<blockquote>
<p>Example request: Refreshing tokens</p>
</blockquote>
<pre><code class="language-php">
$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    '/api/revoke-token',
    [
        'headers' =&gt; [
            'Accept' =&gt; 'application/json',
            'Authorization' =&gt; 'Bearer {ACCESS_TOKEN}',
        ]
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre>
<h3>Request&nbsp;&nbsp;&nbsp;</h3>
<p>
    <small class="badge badge-green">GET</small>
    <b><code>api/revoke-token</code></b>
</p>

        <h1 id="clients">Clients</h1>

    

            <h2 id="clients-POSTapi-v1-clients-create">Create a new client</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-clients-create">
<blockquote>Example request:</blockquote>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;post(
    'https://www.ampp.dev/api/v1/clients/create',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'type' =&gt; 'culpa',
            'first_name' =&gt; 'wvgkbmoafpufthbehtfihynqvaydqtxjgslanyulaktkdtvhazwjlnllmeldhgpkkngyspeitrrtvrhrwcshdeztyuybyxkljwoaitdofcfqcqjhqvgdondmvokswxwrsyjfqibkoympehvkpijhblaqowbgrmnvcrpcteortzsittkiiahqpsqnyvnaiofrvricdt',
            'last_name' =&gt; 'cbupxxsgdltewubecpnfirufnjkavefdrblhqmojqrecgedagshgxprtgvpuregaczjpehkmtuwxjsuemipgzse',
            'company' =&gt; 'eucllcggiofaluazctxq',
            'email' =&gt; 'daibqxghcqbeteudizvzotomlwefnrlbibhetpxqzvqgtntostkzifhpcrdrjyqe',
            'phone' =&gt; 'avcdkrviwsyllmnmrngczyegjumegtjduomibujifmfskqzqfxgpzqfgpijopvwwyyqtkjxoxzqwfkhecxkfrdogfwhrekwqclzbwnhmjvadmelvyydv',
            'vat' =&gt; 'ixwjfvuynpmsfmuhhdrmxwrxqqcqocurqgxsxhlxkgferypcamazkdbkdrljlxybmngeanwgjcwzkfcnynixbchyziphqvibfpgkzaxuiqfjzzeflvvgfdwwgajofyrxusgohyiftbqaqjmxoyjjiyhctaqdnwkoisarvawfubhuwnlfqvzspizpbugqzleltuvsqwqimyaxyrigavwdcdvddhlmmoxaomkgxkzu',
            'street' =&gt; 'wtfzddnqmyazwjnbxukxmevl',
            'number' =&gt; 'ypoasgsqvhgkjslexvkbfaqxvwmnpfyfichzvhckdkneehplopustzyecmufnlxpgqtswotwaeypmafdmsyrlczvqisqroeiryvlkhqqmraagj',
            'postal' =&gt; 'dgnhyzsoyueeuptktlusaiwzilrvprxcztxwolbbjmypftetxymvmyswcfzcpltcbsjcolpcnevwjybmvejixmjuipqhocudhnixnecunwwlsmlxcekibj',
            'city' =&gt; 'buhevxgevcauakfuanrustdemvikhyqyepoivuujlnpbxtyrheyfmiafprdddltqqxpndmtirxm',
            'country' =&gt; 'mtdvpfeymrfoqtjvjwdgtqafwxuofnsiphewppjsxoerezxlnkzgssmugegxbkftzqgsqvxdwpbuvpynlzfkkckvikyrtptvxwhbokbwwpr',
            'description' =&gt; 'sed',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://www.ampp.dev/api/v1/clients/create" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"type\": \"culpa\",
    \"first_name\": \"wvgkbmoafpufthbehtfihynqvaydqtxjgslanyulaktkdtvhazwjlnllmeldhgpkkngyspeitrrtvrhrwcshdeztyuybyxkljwoaitdofcfqcqjhqvgdondmvokswxwrsyjfqibkoympehvkpijhblaqowbgrmnvcrpcteortzsittkiiahqpsqnyvnaiofrvricdt\",
    \"last_name\": \"cbupxxsgdltewubecpnfirufnjkavefdrblhqmojqrecgedagshgxprtgvpuregaczjpehkmtuwxjsuemipgzse\",
    \"company\": \"eucllcggiofaluazctxq\",
    \"email\": \"daibqxghcqbeteudizvzotomlwefnrlbibhetpxqzvqgtntostkzifhpcrdrjyqe\",
    \"phone\": \"avcdkrviwsyllmnmrngczyegjumegtjduomibujifmfskqzqfxgpzqfgpijopvwwyyqtkjxoxzqwfkhecxkfrdogfwhrekwqclzbwnhmjvadmelvyydv\",
    \"vat\": \"ixwjfvuynpmsfmuhhdrmxwrxqqcqocurqgxsxhlxkgferypcamazkdbkdrljlxybmngeanwgjcwzkfcnynixbchyziphqvibfpgkzaxuiqfjzzeflvvgfdwwgajofyrxusgohyiftbqaqjmxoyjjiyhctaqdnwkoisarvawfubhuwnlfqvzspizpbugqzleltuvsqwqimyaxyrigavwdcdvddhlmmoxaomkgxkzu\",
    \"street\": \"wtfzddnqmyazwjnbxukxmevl\",
    \"number\": \"ypoasgsqvhgkjslexvkbfaqxvwmnpfyfichzvhckdkneehplopustzyecmufnlxpgqtswotwaeypmafdmsyrlczvqisqroeiryvlkhqqmraagj\",
    \"postal\": \"dgnhyzsoyueeuptktlusaiwzilrvprxcztxwolbbjmypftetxymvmyswcfzcpltcbsjcolpcnevwjybmvejixmjuipqhocudhnixnecunwwlsmlxcekibj\",
    \"city\": \"buhevxgevcauakfuanrustdemvikhyqyepoivuujlnpbxtyrheyfmiafprdddltqqxpndmtirxm\",
    \"country\": \"mtdvpfeymrfoqtjvjwdgtqafwxuofnsiphewppjsxoerezxlnkzgssmugegxbkftzqgsqvxdwpbuvpynlzfkkckvikyrtptvxwhbokbwwpr\",
    \"description\": \"sed\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://www.ampp.dev/api/v1/clients/create"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "culpa",
    "first_name": "wvgkbmoafpufthbehtfihynqvaydqtxjgslanyulaktkdtvhazwjlnllmeldhgpkkngyspeitrrtvrhrwcshdeztyuybyxkljwoaitdofcfqcqjhqvgdondmvokswxwrsyjfqibkoympehvkpijhblaqowbgrmnvcrpcteortzsittkiiahqpsqnyvnaiofrvricdt",
    "last_name": "cbupxxsgdltewubecpnfirufnjkavefdrblhqmojqrecgedagshgxprtgvpuregaczjpehkmtuwxjsuemipgzse",
    "company": "eucllcggiofaluazctxq",
    "email": "daibqxghcqbeteudizvzotomlwefnrlbibhetpxqzvqgtntostkzifhpcrdrjyqe",
    "phone": "avcdkrviwsyllmnmrngczyegjumegtjduomibujifmfskqzqfxgpzqfgpijopvwwyyqtkjxoxzqwfkhecxkfrdogfwhrekwqclzbwnhmjvadmelvyydv",
    "vat": "ixwjfvuynpmsfmuhhdrmxwrxqqcqocurqgxsxhlxkgferypcamazkdbkdrljlxybmngeanwgjcwzkfcnynixbchyziphqvibfpgkzaxuiqfjzzeflvvgfdwwgajofyrxusgohyiftbqaqjmxoyjjiyhctaqdnwkoisarvawfubhuwnlfqvzspizpbugqzleltuvsqwqimyaxyrigavwdcdvddhlmmoxaomkgxkzu",
    "street": "wtfzddnqmyazwjnbxukxmevl",
    "number": "ypoasgsqvhgkjslexvkbfaqxvwmnpfyfichzvhckdkneehplopustzyecmufnlxpgqtswotwaeypmafdmsyrlczvqisqroeiryvlkhqqmraagj",
    "postal": "dgnhyzsoyueeuptktlusaiwzilrvprxcztxwolbbjmypftetxymvmyswcfzcpltcbsjcolpcnevwjybmvejixmjuipqhocudhnixnecunwwlsmlxcekibj",
    "city": "buhevxgevcauakfuanrustdemvikhyqyepoivuujlnpbxtyrheyfmiafprdddltqqxpndmtirxm",
    "country": "mtdvpfeymrfoqtjvjwdgtqafwxuofnsiphewppjsxoerezxlnkzgssmugegxbkftzqgsqvxdwpbuvpynlzfkkckvikyrtptvxwhbokbwwpr",
    "description": "sed"
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-clients-create">
</span>
<span id="execution-results-POSTapi-v1-clients-create" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-clients-create"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-clients-create"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-clients-create" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-clients-create"></code></pre>
</span>
<form id="form-POSTapi-v1-clients-create" data-method="POST"
      data-path="api/v1/clients/create"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-clients-create', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-clients-create"
                    onclick="tryItOut('POSTapi-v1-clients-create');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-clients-create"
                    onclick="cancelTryOut('POSTapi-v1-clients-create');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-clients-create" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/clients/create</code></b>
        </p>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <p>
            <b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="type"
               data-endpoint="POSTapi-v1-clients-create"
               value="culpa"
               data-component="body" hidden>
    <br>
<p>The type of client, this can be client, lead or bms</p>
        </p>
                <p>
            <b><code>first_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="first_name"
               data-endpoint="POSTapi-v1-clients-create"
               value="wvgkbmoafpufthbehtfihynqvaydqtxjgslanyulaktkdtvhazwjlnllmeldhgpkkngyspeitrrtvrhrwcshdeztyuybyxkljwoaitdofcfqcqjhqvgdondmvokswxwrsyjfqibkoympehvkpijhblaqowbgrmnvcrpcteortzsittkiiahqpsqnyvnaiofrvricdt"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>last_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="last_name"
               data-endpoint="POSTapi-v1-clients-create"
               value="cbupxxsgdltewubecpnfirufnjkavefdrblhqmojqrecgedagshgxprtgvpuregaczjpehkmtuwxjsuemipgzse"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>company</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="company"
               data-endpoint="POSTapi-v1-clients-create"
               value="eucllcggiofaluazctxq"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>email</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="email"
               data-endpoint="POSTapi-v1-clients-create"
               value="daibqxghcqbeteudizvzotomlwefnrlbibhetpxqzvqgtntostkzifhpcrdrjyqe"
               data-component="body" hidden>
    <br>
<p>Must be a valid email address. Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="phone"
               data-endpoint="POSTapi-v1-clients-create"
               value="avcdkrviwsyllmnmrngczyegjumegtjduomibujifmfskqzqfxgpzqfgpijopvwwyyqtkjxoxzqwfkhecxkfrdogfwhrekwqclzbwnhmjvadmelvyydv"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>vat</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="vat"
               data-endpoint="POSTapi-v1-clients-create"
               value="ixwjfvuynpmsfmuhhdrmxwrxqqcqocurqgxsxhlxkgferypcamazkdbkdrljlxybmngeanwgjcwzkfcnynixbchyziphqvibfpgkzaxuiqfjzzeflvvgfdwwgajofyrxusgohyiftbqaqjmxoyjjiyhctaqdnwkoisarvawfubhuwnlfqvzspizpbugqzleltuvsqwqimyaxyrigavwdcdvddhlmmoxaomkgxkzu"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>street</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="street"
               data-endpoint="POSTapi-v1-clients-create"
               value="wtfzddnqmyazwjnbxukxmevl"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>number</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="number"
               data-endpoint="POSTapi-v1-clients-create"
               value="ypoasgsqvhgkjslexvkbfaqxvwmnpfyfichzvhckdkneehplopustzyecmufnlxpgqtswotwaeypmafdmsyrlczvqisqroeiryvlkhqqmraagj"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>postal</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="postal"
               data-endpoint="POSTapi-v1-clients-create"
               value="dgnhyzsoyueeuptktlusaiwzilrvprxcztxwolbbjmypftetxymvmyswcfzcpltcbsjcolpcnevwjybmvejixmjuipqhocudhnixnecunwwlsmlxcekibj"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>city</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="city"
               data-endpoint="POSTapi-v1-clients-create"
               value="buhevxgevcauakfuanrustdemvikhyqyepoivuujlnpbxtyrheyfmiafprdddltqqxpndmtirxm"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>country</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="country"
               data-endpoint="POSTapi-v1-clients-create"
               value="mtdvpfeymrfoqtjvjwdgtqafwxuofnsiphewppjsxoerezxlnkzgssmugegxbkftzqgsqvxdwpbuvpynlzfkkckvikyrtptvxwhbokbwwpr"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>description</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="description"
               data-endpoint="POSTapi-v1-clients-create"
               value="sed"
               data-component="body" hidden>
    <br>

        </p>
        </form>

            <h2 id="clients-GETapi-v1-clients">Get all clients</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-clients">
<blockquote>Example request:</blockquote>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'https://www.ampp.dev/api/v1/clients',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://www.ampp.dev/api/v1/clients" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://www.ampp.dev/api/v1/clients"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers,
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-clients">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 58
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-clients" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-clients"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-clients"></code></pre>
</span>
<span id="execution-error-GETapi-v1-clients" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-clients"></code></pre>
</span>
<form id="form-GETapi-v1-clients" data-method="GET"
      data-path="api/v1/clients"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-clients', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-clients"
                    onclick="tryItOut('GETapi-v1-clients');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-clients"
                    onclick="cancelTryOut('GETapi-v1-clients');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-clients" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/clients</code></b>
        </p>
                    </form>

            <h2 id="clients-PATCHapi-v1-clients-edit">Update an existing client</h2>

<p>
</p>



<span id="example-requests-PATCHapi-v1-clients-edit">
<blockquote>Example request:</blockquote>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;patch(
    'https://www.ampp.dev/api/v1/clients/edit',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'first_name' =&gt; 'fskmrnxvobnjxchaomrfbntmrsheqwyokwvtnuntdnoqkvazdgpthxzsulgrdbzrgoxozagtaelbtwmeavddpzrfadjenmd',
            'last_name' =&gt; 'ukukutodhhlifpvtwytugsfahylkezdvbnpfkkckdctwttjgmaojbxkvieumyqtxadufgbdrfxxoqzn',
            'company' =&gt; 'clyxkzmzebqwtfaeborqxllbelfeatolizqvuohhrgihrcuevkxzijutujjnaepnkpdhlnakmwspwxapsvtlbgejufuwzkayoapexbtngprdbpcxxqguvnbctxgvkcqktpncynjuuegmbusulmxtyqtbwukukuqdcjypsekpckdgsfsstltarjhtmwynpwvffwq',
            'email' =&gt; 'zceiinqaxofpkowvszjstkfeoiohqihrdbhqyiclrlumcpcculkegozjdzwbaczvxydfkdyqpvwepxqthakekgqsoqclvdrhgfjifwfmcdilgprwyxximddvtmhswgujydfrdycfcsqtlmcdnckbphebjssheuqkesaltnaikefhnvaxklfccgrutviaklfeezvqavdroyfzwshxe',
            'phone' =&gt; 'sutxaspvjthvapbibkrnxyqqxvccvsxqfvaynnhkilmipaflfhcdspobjrnvryxmvuhqthbpmnggomnhfepdkezvcugyfrpbewsjvadrwzpnublhrelxbrfssnmhzunvtbgokxvsjpfechhnatqfoxccyeizekvdujitnakrgfwffdkunvjpipplfzuwpxizurvxosapoytpopieryucrxinwamssspwhatrolqxkecnzaluennflitx',
            'vat' =&gt; 'omppqeenadcvsooppizjumqjscxnupxkigxlbcufisodaabionmwolxfyozbgutp',
            'street' =&gt; 'sncxdzorukrhuufzwtktdkckpwykoeldjxuempaxgltlhhlhwzvzhyogihlqjdpsucpaggpzhtxiafijxcvlxzh',
            'number' =&gt; 'muqhvgddxpdxnfxujzrzvwrbkshnepigvbkkqvmumdkgwgakzonjibjemwqyophnhrdgdwstwiyhchynpfulahhnivijzfopdujysvvllbunqvjcweuvuyucvyejaamupwdsexjidrzouimioayseyxwmpxzjgumdobnftxsrezgqouuziiddjivmdn',
            'postal' =&gt; 'jucmrdzurwhsessfhdabecqtpxzpklzqwjgggtyglpdzcdlmaqxbjczfsooabuyrogalzogozpvjmdrxfbldqcjsafzcgbvtmtrnwcne',
            'city' =&gt; 'rbajzcmeuyybne',
            'country' =&gt; 'qozikokxbepqzgmdbrurekhamhptaeuljqdpikilxpbmervepqwztbdjeaawmsxbvwfulphelxbrbwvjlnhyxpqecpvbeyvowhqgpeejrrixgrosntntfpxj',
            'description' =&gt; 'quia',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "https://www.ampp.dev/api/v1/clients/edit" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"first_name\": \"fskmrnxvobnjxchaomrfbntmrsheqwyokwvtnuntdnoqkvazdgpthxzsulgrdbzrgoxozagtaelbtwmeavddpzrfadjenmd\",
    \"last_name\": \"ukukutodhhlifpvtwytugsfahylkezdvbnpfkkckdctwttjgmaojbxkvieumyqtxadufgbdrfxxoqzn\",
    \"company\": \"clyxkzmzebqwtfaeborqxllbelfeatolizqvuohhrgihrcuevkxzijutujjnaepnkpdhlnakmwspwxapsvtlbgejufuwzkayoapexbtngprdbpcxxqguvnbctxgvkcqktpncynjuuegmbusulmxtyqtbwukukuqdcjypsekpckdgsfsstltarjhtmwynpwvffwq\",
    \"email\": \"zceiinqaxofpkowvszjstkfeoiohqihrdbhqyiclrlumcpcculkegozjdzwbaczvxydfkdyqpvwepxqthakekgqsoqclvdrhgfjifwfmcdilgprwyxximddvtmhswgujydfrdycfcsqtlmcdnckbphebjssheuqkesaltnaikefhnvaxklfccgrutviaklfeezvqavdroyfzwshxe\",
    \"phone\": \"sutxaspvjthvapbibkrnxyqqxvccvsxqfvaynnhkilmipaflfhcdspobjrnvryxmvuhqthbpmnggomnhfepdkezvcugyfrpbewsjvadrwzpnublhrelxbrfssnmhzunvtbgokxvsjpfechhnatqfoxccyeizekvdujitnakrgfwffdkunvjpipplfzuwpxizurvxosapoytpopieryucrxinwamssspwhatrolqxkecnzaluennflitx\",
    \"vat\": \"omppqeenadcvsooppizjumqjscxnupxkigxlbcufisodaabionmwolxfyozbgutp\",
    \"street\": \"sncxdzorukrhuufzwtktdkckpwykoeldjxuempaxgltlhhlhwzvzhyogihlqjdpsucpaggpzhtxiafijxcvlxzh\",
    \"number\": \"muqhvgddxpdxnfxujzrzvwrbkshnepigvbkkqvmumdkgwgakzonjibjemwqyophnhrdgdwstwiyhchynpfulahhnivijzfopdujysvvllbunqvjcweuvuyucvyejaamupwdsexjidrzouimioayseyxwmpxzjgumdobnftxsrezgqouuziiddjivmdn\",
    \"postal\": \"jucmrdzurwhsessfhdabecqtpxzpklzqwjgggtyglpdzcdlmaqxbjczfsooabuyrogalzogozpvjmdrxfbldqcjsafzcgbvtmtrnwcne\",
    \"city\": \"rbajzcmeuyybne\",
    \"country\": \"qozikokxbepqzgmdbrurekhamhptaeuljqdpikilxpbmervepqwztbdjeaawmsxbvwfulphelxbrbwvjlnhyxpqecpvbeyvowhqgpeejrrixgrosntntfpxj\",
    \"description\": \"quia\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://www.ampp.dev/api/v1/clients/edit"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "first_name": "fskmrnxvobnjxchaomrfbntmrsheqwyokwvtnuntdnoqkvazdgpthxzsulgrdbzrgoxozagtaelbtwmeavddpzrfadjenmd",
    "last_name": "ukukutodhhlifpvtwytugsfahylkezdvbnpfkkckdctwttjgmaojbxkvieumyqtxadufgbdrfxxoqzn",
    "company": "clyxkzmzebqwtfaeborqxllbelfeatolizqvuohhrgihrcuevkxzijutujjnaepnkpdhlnakmwspwxapsvtlbgejufuwzkayoapexbtngprdbpcxxqguvnbctxgvkcqktpncynjuuegmbusulmxtyqtbwukukuqdcjypsekpckdgsfsstltarjhtmwynpwvffwq",
    "email": "zceiinqaxofpkowvszjstkfeoiohqihrdbhqyiclrlumcpcculkegozjdzwbaczvxydfkdyqpvwepxqthakekgqsoqclvdrhgfjifwfmcdilgprwyxximddvtmhswgujydfrdycfcsqtlmcdnckbphebjssheuqkesaltnaikefhnvaxklfccgrutviaklfeezvqavdroyfzwshxe",
    "phone": "sutxaspvjthvapbibkrnxyqqxvccvsxqfvaynnhkilmipaflfhcdspobjrnvryxmvuhqthbpmnggomnhfepdkezvcugyfrpbewsjvadrwzpnublhrelxbrfssnmhzunvtbgokxvsjpfechhnatqfoxccyeizekvdujitnakrgfwffdkunvjpipplfzuwpxizurvxosapoytpopieryucrxinwamssspwhatrolqxkecnzaluennflitx",
    "vat": "omppqeenadcvsooppizjumqjscxnupxkigxlbcufisodaabionmwolxfyozbgutp",
    "street": "sncxdzorukrhuufzwtktdkckpwykoeldjxuempaxgltlhhlhwzvzhyogihlqjdpsucpaggpzhtxiafijxcvlxzh",
    "number": "muqhvgddxpdxnfxujzrzvwrbkshnepigvbkkqvmumdkgwgakzonjibjemwqyophnhrdgdwstwiyhchynpfulahhnivijzfopdujysvvllbunqvjcweuvuyucvyejaamupwdsexjidrzouimioayseyxwmpxzjgumdobnftxsrezgqouuziiddjivmdn",
    "postal": "jucmrdzurwhsessfhdabecqtpxzpklzqwjgggtyglpdzcdlmaqxbjczfsooabuyrogalzogozpvjmdrxfbldqcjsafzcgbvtmtrnwcne",
    "city": "rbajzcmeuyybne",
    "country": "qozikokxbepqzgmdbrurekhamhptaeuljqdpikilxpbmervepqwztbdjeaawmsxbvwfulphelxbrbwvjlnhyxpqecpvbeyvowhqgpeejrrixgrosntntfpxj",
    "description": "quia"
};

fetch(url, {
    method: "PATCH",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-PATCHapi-v1-clients-edit">
</span>
<span id="execution-results-PATCHapi-v1-clients-edit" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-v1-clients-edit"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-v1-clients-edit"></code></pre>
</span>
<span id="execution-error-PATCHapi-v1-clients-edit" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-v1-clients-edit"></code></pre>
</span>
<form id="form-PATCHapi-v1-clients-edit" data-method="PATCH"
      data-path="api/v1/clients/edit"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-v1-clients-edit', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-v1-clients-edit"
                    onclick="tryItOut('PATCHapi-v1-clients-edit');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-v1-clients-edit"
                    onclick="cancelTryOut('PATCHapi-v1-clients-edit');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-v1-clients-edit" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/clients/edit</code></b>
        </p>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <p>
            <b><code>type</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="type"
               data-endpoint="PATCHapi-v1-clients-edit"
               value=""
               data-component="body" hidden>
    <br>

        </p>
                <p>
            <b><code>first_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="first_name"
               data-endpoint="PATCHapi-v1-clients-edit"
               value="fskmrnxvobnjxchaomrfbntmrsheqwyokwvtnuntdnoqkvazdgpthxzsulgrdbzrgoxozagtaelbtwmeavddpzrfadjenmd"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>last_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="last_name"
               data-endpoint="PATCHapi-v1-clients-edit"
               value="ukukutodhhlifpvtwytugsfahylkezdvbnpfkkckdctwttjgmaojbxkvieumyqtxadufgbdrfxxoqzn"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>company</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="company"
               data-endpoint="PATCHapi-v1-clients-edit"
               value="clyxkzmzebqwtfaeborqxllbelfeatolizqvuohhrgihrcuevkxzijutujjnaepnkpdhlnakmwspwxapsvtlbgejufuwzkayoapexbtngprdbpcxxqguvnbctxgvkcqktpncynjuuegmbusulmxtyqtbwukukuqdcjypsekpckdgsfsstltarjhtmwynpwvffwq"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>email</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="email"
               data-endpoint="PATCHapi-v1-clients-edit"
               value="zceiinqaxofpkowvszjstkfeoiohqihrdbhqyiclrlumcpcculkegozjdzwbaczvxydfkdyqpvwepxqthakekgqsoqclvdrhgfjifwfmcdilgprwyxximddvtmhswgujydfrdycfcsqtlmcdnckbphebjssheuqkesaltnaikefhnvaxklfccgrutviaklfeezvqavdroyfzwshxe"
               data-component="body" hidden>
    <br>
<p>Must be a valid email address. Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>phone</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="phone"
               data-endpoint="PATCHapi-v1-clients-edit"
               value="sutxaspvjthvapbibkrnxyqqxvccvsxqfvaynnhkilmipaflfhcdspobjrnvryxmvuhqthbpmnggomnhfepdkezvcugyfrpbewsjvadrwzpnublhrelxbrfssnmhzunvtbgokxvsjpfechhnatqfoxccyeizekvdujitnakrgfwffdkunvjpipplfzuwpxizurvxosapoytpopieryucrxinwamssspwhatrolqxkecnzaluennflitx"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>vat</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="vat"
               data-endpoint="PATCHapi-v1-clients-edit"
               value="omppqeenadcvsooppizjumqjscxnupxkigxlbcufisodaabionmwolxfyozbgutp"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>street</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="street"
               data-endpoint="PATCHapi-v1-clients-edit"
               value="sncxdzorukrhuufzwtktdkckpwykoeldjxuempaxgltlhhlhwzvzhyogihlqjdpsucpaggpzhtxiafijxcvlxzh"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>number</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="number"
               data-endpoint="PATCHapi-v1-clients-edit"
               value="muqhvgddxpdxnfxujzrzvwrbkshnepigvbkkqvmumdkgwgakzonjibjemwqyophnhrdgdwstwiyhchynpfulahhnivijzfopdujysvvllbunqvjcweuvuyucvyejaamupwdsexjidrzouimioayseyxwmpxzjgumdobnftxsrezgqouuziiddjivmdn"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>postal</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="postal"
               data-endpoint="PATCHapi-v1-clients-edit"
               value="jucmrdzurwhsessfhdabecqtpxzpklzqwjgggtyglpdzcdlmaqxbjczfsooabuyrogalzogozpvjmdrxfbldqcjsafzcgbvtmtrnwcne"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>city</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="city"
               data-endpoint="PATCHapi-v1-clients-edit"
               value="rbajzcmeuyybne"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>country</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="country"
               data-endpoint="PATCHapi-v1-clients-edit"
               value="qozikokxbepqzgmdbrurekhamhptaeuljqdpikilxpbmervepqwztbdjeaawmsxbvwfulphelxbrbwvjlnhyxpqecpvbeyvowhqgpeejrrixgrosntntfpxj"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
        </p>
                <p>
            <b><code>description</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="description"
               data-endpoint="PATCHapi-v1-clients-edit"
               value="quia"
               data-component="body" hidden>
    <br>

        </p>
        </form>

        <h1 id="invoices">Invoices</h1>

    

            <h2 id="invoices-GETapi-v1-invoices">Get all invoices for a client</h2>

<p>
</p>



<span id="example-requests-GETapi-v1-invoices">
<blockquote>Example request:</blockquote>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;get(
    'https://www.ampp.dev/api/v1/invoices',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'client_id' =&gt; '42',
            'from' =&gt; '2022-06-22',
            'till' =&gt; '2117-11-07',
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "https://www.ampp.dev/api/v1/invoices" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"client_id\": \"42\",
    \"from\": \"2022-06-22\",
    \"till\": \"2117-11-07\"
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://www.ampp.dev/api/v1/invoices"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "client_id": "42",
    "from": "2022-06-22",
    "till": "2117-11-07"
};

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-invoices">
            <blockquote>
            <p>Example response (401):</p>
        </blockquote>
                <details class="annotation">
            <summary>
                <small onclick="textContent = parentElement.parentElement.open ? 'Show headers' : 'Hide headers'">Show headers</small>
            </summary>
            <pre><code class="language-http">cache-control: no-cache, private
content-type: application/json
x-ratelimit-limit: 60
x-ratelimit-remaining: 59
access-control-allow-origin: *
 </code></pre>
        </details>         <pre>

<code class="language-json">{
    &quot;message&quot;: &quot;Unauthenticated.&quot;
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-invoices" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-invoices"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-invoices"></code></pre>
</span>
<span id="execution-error-GETapi-v1-invoices" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-invoices"></code></pre>
</span>
<form id="form-GETapi-v1-invoices" data-method="GET"
      data-path="api/v1/invoices"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-invoices', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-invoices"
                    onclick="tryItOut('GETapi-v1-invoices');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-invoices"
                    onclick="cancelTryOut('GETapi-v1-invoices');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-invoices" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/invoices</code></b>
        </p>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <p>
            <b><code>client_id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="client_id"
               data-endpoint="GETapi-v1-invoices"
               value="42"
               data-component="body" hidden>
    <br>
<p>Must be one of <code>4</code>, <code>5</code>, <code>6</code>, <code>10</code>, <code>12</code>, <code>13</code>, <code>14</code>, <code>17</code>, <code>20</code>, <code>21</code>, <code>22</code>, <code>23</code>, <code>24</code>, <code>26</code>, <code>27</code>, <code>28</code>, <code>31</code>, <code>34</code>, <code>36</code>, <code>38</code>, <code>39</code>, <code>40</code>, <code>41</code>, <code>42</code>, <code>43</code>, <code>44</code>, <code>45</code>, <code>46</code>, <code>56</code>, <code>57</code>, <code>58</code>, <code>59</code>, <code>60</code>, <code>61</code>, <code>62</code>, <code>66</code>, <code>67</code>, <code>68</code>, <code>69</code>, <code>70</code>, <code>71</code>, <code>72</code>, <code>73</code>, <code>74</code>, <code>75</code>, <code>76</code>, <code>77</code>, <code>78</code>, <code>79</code>, <code>80</code>, <code>81</code>, <code>82</code>, <code>83</code>, <code>84</code>, <code>85</code>, <code>86</code>, <code>87</code>, or <code>88</code>.</p>
        </p>
                <p>
            <b><code>type</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="type"
               data-endpoint="GETapi-v1-invoices"
               value=""
               data-component="body" hidden>
    <br>

        </p>
                <p>
            <b><code>from</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="from"
               data-endpoint="GETapi-v1-invoices"
               value="2022-06-22"
               data-component="body" hidden>
    <br>
<p>Must be a valid date in the format <code>Y-m-d</code>.</p>
        </p>
                <p>
            <b><code>till</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="till"
               data-endpoint="GETapi-v1-invoices"
               value="2117-11-07"
               data-component="body" hidden>
    <br>
<p>This field is required when <code>from</code> is present.  Must be a valid date in the format <code>Y-m-d</code>. Must be a date after or equal to <code>from</code>.</p>
        </p>
        </form>

            <h2 id="invoices-POSTapi-v1-invoices-create">Create a new invoice</h2>

<p>
</p>



<span id="example-requests-POSTapi-v1-invoices-create">
<blockquote>Example request:</blockquote>


<div class="php-example">
    <pre><code class="language-php">$client = new \GuzzleHttp\Client();
$response = $client-&gt;post(
    'https://www.ampp.dev/api/v1/invoices/create',
    [
        'headers' =&gt; [
            'Content-Type' =&gt; 'application/json',
            'Accept' =&gt; 'application/json',
        ],
        'json' =&gt; [
            'type' =&gt; 'enim',
            'client_id' =&gt; '61',
            'status' =&gt; 18,
            'notes' =&gt; 'rerum',
            'pdf_comment' =&gt; 'hic',
            'expiration_date' =&gt; '2022-06-22',
            'custom_created_at' =&gt; '2022-06-22',
            'billing_lines' =&gt; [
                [
                    'type' =&gt; 'text',
                    'order' =&gt; 4,
                    'text' =&gt; 'onzhkenmungxlyjltdpqanhuriuyvjzwfoskqtpcazigudqbiixyheejerleqlfugebcwfjzfvcdhjmcodqvqmuhkguxxkykzdyfmtuqgeceqrbhvwlyipsvdmpzmxyxhcznifnsypswmbyaaekvozplzjkvmbshyezujazzgsxagdtcjzurka',
                    'price' =&gt; 370359235.4,
                    'subtotal' =&gt; 572877.19,
                    'amount' =&gt; 58970262.1,
                    'vat' =&gt; 3.28,
                    'discount' =&gt; 58302.8,
                    'description' =&gt; 'ovwmlznyqwtxtwebmmzbcqncmsrshbhhknjzatqmpskcweoebmbeyplmadjitkietcnhzkxfhg',
                ],
            ],
        ],
    ]
);
$body = $response-&gt;getBody();
print_r(json_decode((string) $body));</code></pre></div>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "https://www.ampp.dev/api/v1/invoices/create" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"type\": \"enim\",
    \"client_id\": \"61\",
    \"status\": 18,
    \"notes\": \"rerum\",
    \"pdf_comment\": \"hic\",
    \"expiration_date\": \"2022-06-22\",
    \"custom_created_at\": \"2022-06-22\",
    \"billing_lines\": [
        {
            \"type\": \"text\",
            \"order\": 4,
            \"text\": \"onzhkenmungxlyjltdpqanhuriuyvjzwfoskqtpcazigudqbiixyheejerleqlfugebcwfjzfvcdhjmcodqvqmuhkguxxkykzdyfmtuqgeceqrbhvwlyipsvdmpzmxyxhcznifnsypswmbyaaekvozplzjkvmbshyezujazzgsxagdtcjzurka\",
            \"price\": 370359235.4,
            \"subtotal\": 572877.19,
            \"amount\": 58970262.1,
            \"vat\": 3.28,
            \"discount\": 58302.8,
            \"description\": \"ovwmlznyqwtxtwebmmzbcqncmsrshbhhknjzatqmpskcweoebmbeyplmadjitkietcnhzkxfhg\"
        }
    ]
}"
</code></pre></div>


<div class="javascript-example">
    <pre><code class="language-javascript">const url = new URL(
    "https://www.ampp.dev/api/v1/invoices/create"
);

const headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "type": "enim",
    "client_id": "61",
    "status": 18,
    "notes": "rerum",
    "pdf_comment": "hic",
    "expiration_date": "2022-06-22",
    "custom_created_at": "2022-06-22",
    "billing_lines": [
        {
            "type": "text",
            "order": 4,
            "text": "onzhkenmungxlyjltdpqanhuriuyvjzwfoskqtpcazigudqbiixyheejerleqlfugebcwfjzfvcdhjmcodqvqmuhkguxxkykzdyfmtuqgeceqrbhvwlyipsvdmpzmxyxhcznifnsypswmbyaaekvozplzjkvmbshyezujazzgsxagdtcjzurka",
            "price": 370359235.4,
            "subtotal": 572877.19,
            "amount": 58970262.1,
            "vat": 3.28,
            "discount": 58302.8,
            "description": "ovwmlznyqwtxtwebmmzbcqncmsrshbhhknjzatqmpskcweoebmbeyplmadjitkietcnhzkxfhg"
        }
    ]
};

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response =&gt; response.json());</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-invoices-create">
</span>
<span id="execution-results-POSTapi-v1-invoices-create" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-invoices-create"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-invoices-create"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-invoices-create" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-invoices-create"></code></pre>
</span>
<form id="form-POSTapi-v1-invoices-create" data-method="POST"
      data-path="api/v1/invoices/create"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}'
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-invoices-create', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-invoices-create"
                    onclick="tryItOut('POSTapi-v1-invoices-create');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-invoices-create"
                    onclick="cancelTryOut('POSTapi-v1-invoices-create');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-invoices-create" hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/invoices/create</code></b>
        </p>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <p>
            <b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="type"
               data-endpoint="POSTapi-v1-invoices-create"
               value="enim"
               data-component="body" hidden>
    <br>
<p>The type of invoice, this can be debit or credit</p>
        </p>
                <p>
            <b><code>client_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
                <input type="number"
               name="client_id"
               data-endpoint="POSTapi-v1-invoices-create"
               value="61"
               data-component="body" hidden>
    <br>
<p>Must be one of <code>4</code>, <code>5</code>, <code>6</code>, <code>10</code>, <code>12</code>, <code>13</code>, <code>14</code>, <code>17</code>, <code>20</code>, <code>21</code>, <code>22</code>, <code>23</code>, <code>24</code>, <code>26</code>, <code>27</code>, <code>28</code>, <code>31</code>, <code>34</code>, <code>36</code>, <code>38</code>, <code>39</code>, <code>40</code>, <code>41</code>, <code>42</code>, <code>43</code>, <code>44</code>, <code>45</code>, <code>46</code>, <code>56</code>, <code>57</code>, <code>58</code>, <code>59</code>, <code>60</code>, <code>61</code>, <code>62</code>, <code>66</code>, <code>67</code>, <code>68</code>, <code>69</code>, <code>70</code>, <code>71</code>, <code>72</code>, <code>73</code>, <code>74</code>, <code>75</code>, <code>76</code>, <code>77</code>, <code>78</code>, <code>79</code>, <code>80</code>, <code>81</code>, <code>82</code>, <code>83</code>, <code>84</code>, <code>85</code>, <code>86</code>, <code>87</code>, or <code>88</code>.</p>
        </p>
                <p>
            <b><code>status</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
                <input type="number"
               name="status"
               data-endpoint="POSTapi-v1-invoices-create"
               value="18"
               data-component="body" hidden>
    <br>
<p>The status of the invoice, This can be 0 (draft), 1 (sent), 2 (reminder), 3 (paid)</p>
        </p>
                <p>
            <b><code>notes</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="notes"
               data-endpoint="POSTapi-v1-invoices-create"
               value="rerum"
               data-component="body" hidden>
    <br>

        </p>
                <p>
            <b><code>pdf_comment</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="pdf_comment"
               data-endpoint="POSTapi-v1-invoices-create"
               value="hic"
               data-component="body" hidden>
    <br>

        </p>
                <p>
            <b><code>expiration_date</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="expiration_date"
               data-endpoint="POSTapi-v1-invoices-create"
               value="2022-06-22"
               data-component="body" hidden>
    <br>
<p>Must be a valid date in the format <code>Y-m-d</code>.</p>
        </p>
                <p>
            <b><code>custom_created_at</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="custom_created_at"
               data-endpoint="POSTapi-v1-invoices-create"
               value="2022-06-22"
               data-component="body" hidden>
    <br>
<p>Must be a valid date in the format <code>Y-m-d</code>.</p>
        </p>
                <p>
        <details>
            <summary style="padding-bottom: 10px;">
                <b><code>billing_lines</code></b>&nbsp;&nbsp;<small>object[]</small>     <i>optional</i> &nbsp;
<br>

            </summary>
                                                <p>
                        <b><code>billing_lines[].type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="billing_lines.0.type"
               data-endpoint="POSTapi-v1-invoices-create"
               value="text"
               data-component="body" hidden>
    <br>
<p>Must be one of <code>header</code> or <code>text</code>.</p>
                    </p>
                                                                <p>
                        <b><code>billing_lines[].order</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
                <input type="number"
               name="billing_lines.0.order"
               data-endpoint="POSTapi-v1-invoices-create"
               value="4"
               data-component="body" hidden>
    <br>

                    </p>
                                                                <p>
                        <b><code>billing_lines[].text</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
                <input type="text"
               name="billing_lines.0.text"
               data-endpoint="POSTapi-v1-invoices-create"
               value="onzhkenmungxlyjltdpqanhuriuyvjzwfoskqtpcazigudqbiixyheejerleqlfugebcwfjzfvcdhjmcodqvqmuhkguxxkykzdyfmtuqgeceqrbhvwlyipsvdmpzmxyxhcznifnsypswmbyaaekvozplzjkvmbshyezujazzgsxagdtcjzurka"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
                    </p>
                                                                <p>
                        <b><code>billing_lines[].price</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
                <input type="number"
               name="billing_lines.0.price"
               data-endpoint="POSTapi-v1-invoices-create"
               value="370359235.4"
               data-component="body" hidden>
    <br>

                    </p>
                                                                <p>
                        <b><code>billing_lines[].subtotal</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
                <input type="number"
               name="billing_lines.0.subtotal"
               data-endpoint="POSTapi-v1-invoices-create"
               value="572877.19"
               data-component="body" hidden>
    <br>

                    </p>
                                                                <p>
                        <b><code>billing_lines[].amount</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
                <input type="number"
               name="billing_lines.0.amount"
               data-endpoint="POSTapi-v1-invoices-create"
               value="58970262.1"
               data-component="body" hidden>
    <br>

                    </p>
                                                                <p>
                        <b><code>billing_lines[].vat</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
                <input type="number"
               name="billing_lines.0.vat"
               data-endpoint="POSTapi-v1-invoices-create"
               value="3.28"
               data-component="body" hidden>
    <br>

                    </p>
                                                                <p>
                        <b><code>billing_lines[].discount</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
                <input type="number"
               name="billing_lines.0.discount"
               data-endpoint="POSTapi-v1-invoices-create"
               value="58302.8"
               data-component="body" hidden>
    <br>

                    </p>
                                                                <p>
                        <b><code>billing_lines[].description</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
                <input type="text"
               name="billing_lines.0.description"
               data-endpoint="POSTapi-v1-invoices-create"
               value="ovwmlznyqwtxtwebmmzbcqncmsrshbhhknjzatqmpskcweoebmbeyplmadjitkietcnhzkxfhg"
               data-component="body" hidden>
    <br>
<p>Must not be greater than 255 characters.</p>
                    </p>
                                    </details>
        </p>
        </form>

    

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="php">php</button>
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                                                        <button type="button" class="lang-button" data-language-name="javascript">javascript</button>
                            </div>
            </div>
</div>
</body>
</html>
