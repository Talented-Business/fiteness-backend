# User   

APIs for managing  user

## update my profile.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X PUT \
    "http://127.0.0.4/api/users/dolores" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/dolores"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "PUT",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-PUTapi-users--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-users--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-users--id-"></code></pre>
</div>
<div id="execution-error-PUTapi-users--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-users--id-"></code></pre>
</div>
<form id="form-PUTapi-users--id-" data-method="PUT" data-path="api/users/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-users--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-users--id-" onclick="tryItOut('PUTapi-users--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-users--id-" onclick="cancelTryOut('PUTapi-users--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-users--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/users/{id}</code></b>
</p>
<p>
<label id="auth-PUTapi-users--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-users--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="PUTapi-users--id-" data-component="url" required  hidden>
<br>
</p>
</form>


## show the user.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/users/repellendus" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/repellendus"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "GET",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-GETapi-users--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-users--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-users--id-"></code></pre>
</div>
<div id="execution-error-GETapi-users--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-users--id-"></code></pre>
</div>
<form id="form-GETapi-users--id-" data-method="GET" data-path="api/users/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-users--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-users--id-" onclick="tryItOut('GETapi-users--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-users--id-" onclick="cancelTryOut('GETapi-users--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-users--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/users/{id}</code></b>
</p>
<p>
<label id="auth-GETapi-users--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-users--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-users--id-" data-component="url" required  hidden>
<br>
</p>
</form>


## delete user.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/users/sed" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/sed"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-DELETEapi-users--id-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-users--id-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-users--id-"></code></pre>
</div>
<div id="execution-error-DELETEapi-users--id-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-users--id-"></code></pre>
</div>
<form id="form-DELETEapi-users--id-" data-method="DELETE" data-path="api/users/{id}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-users--id-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-users--id-" onclick="tryItOut('DELETEapi-users--id-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-users--id-" onclick="cancelTryOut('DELETEapi-users--id-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-users--id-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/users/{id}</code></b>
</p>
<p>
<label id="auth-DELETEapi-users--id-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-users--id-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="DELETEapi-users--id-" data-component="url" required  hidden>
<br>
</p>
</form>


## generate token.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/users/accessToken" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/accessToken"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-POSTapi-users-accessToken" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-users-accessToken"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-users-accessToken"></code></pre>
</div>
<div id="execution-error-POSTapi-users-accessToken" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-users-accessToken"></code></pre>
</div>
<form id="form-POSTapi-users-accessToken" data-method="POST" data-path="api/users/accessToken" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-users-accessToken', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-users-accessToken" onclick="tryItOut('POSTapi-users-accessToken');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-users-accessToken" onclick="cancelTryOut('POSTapi-users-accessToken');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-users-accessToken" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/users/accessToken</code></b>
</p>
<p>
<label id="auth-POSTapi-users-accessToken" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-users-accessToken" data-component="header"></label>
</p>
</form>


## find me.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/users/me" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/me"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-POSTapi-users-me" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-users-me"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-users-me"></code></pre>
</div>
<div id="execution-error-POSTapi-users-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-users-me"></code></pre>
</div>
<form id="form-POSTapi-users-me" data-method="POST" data-path="api/users/me" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-users-me', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-users-me" onclick="tryItOut('POSTapi-users-me');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-users-me" onclick="cancelTryOut('POSTapi-users-me');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-users-me" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/users/me</code></b>
</p>
<p>
<label id="auth-POSTapi-users-me" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-users-me" data-component="header"></label>
</p>
</form>


## update customer info.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/users/customerUpdate" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"first_name":"ad","last_name":"eveniet","username":"quam","gender":"corporis","current_height":"praesentium","whatsapp_phone_number":"voluptatem","country":"fuga","country_code":"iusto","description":"voluptas"}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/customerUpdate"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "first_name": "ad",
    "last_name": "eveniet",
    "username": "quam",
    "gender": "corporis",
    "current_height": "praesentium",
    "whatsapp_phone_number": "voluptatem",
    "country": "fuga",
    "country_code": "iusto",
    "description": "voluptas"
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-POSTapi-users-customerUpdate" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-users-customerUpdate"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-users-customerUpdate"></code></pre>
</div>
<div id="execution-error-POSTapi-users-customerUpdate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-users-customerUpdate"></code></pre>
</div>
<form id="form-POSTapi-users-customerUpdate" data-method="POST" data-path="api/users/customerUpdate" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-users-customerUpdate', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-users-customerUpdate" onclick="tryItOut('POSTapi-users-customerUpdate');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-users-customerUpdate" onclick="cancelTryOut('POSTapi-users-customerUpdate');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-users-customerUpdate" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/users/customerUpdate</code></b>
</p>
<p>
<label id="auth-POSTapi-users-customerUpdate" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-users-customerUpdate" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>first_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="first_name" data-endpoint="POSTapi-users-customerUpdate" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>last_name</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="last_name" data-endpoint="POSTapi-users-customerUpdate" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>username</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="username" data-endpoint="POSTapi-users-customerUpdate" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>gender</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="gender" data-endpoint="POSTapi-users-customerUpdate" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>current_height</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="current_height" data-endpoint="POSTapi-users-customerUpdate" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>whatsapp_phone_number</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="whatsapp_phone_number" data-endpoint="POSTapi-users-customerUpdate" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>country</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="country" data-endpoint="POSTapi-users-customerUpdate" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>country_code</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="country_code" data-endpoint="POSTapi-users-customerUpdate" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>description</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="description" data-endpoint="POSTapi-users-customerUpdate" data-component="body"  hidden>
<br>
</p>

</form>


## update email.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X PUT \
    "http://127.0.0.4/api/users/email-only" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/email-only"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "PUT",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-PUTapi-users-email-only" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-users-email-only"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-users-email-only"></code></pre>
</div>
<div id="execution-error-PUTapi-users-email-only" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-users-email-only"></code></pre>
</div>
<form id="form-PUTapi-users-email-only" data-method="PUT" data-path="api/users/email-only" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-users-email-only', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-users-email-only" onclick="tryItOut('PUTapi-users-email-only');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-users-email-only" onclick="cancelTryOut('PUTapi-users-email-only');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-users-email-only" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/users/email-only</code></b>
</p>
<p>
<label id="auth-PUTapi-users-email-only" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-users-email-only" data-component="header"></label>
</p>
</form>


## update password.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X PUT \
    "http://127.0.0.4/api/users/update-password" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/update-password"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "PUT",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-PUTapi-users-update-password" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-users-update-password"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-users-update-password"></code></pre>
</div>
<div id="execution-error-PUTapi-users-update-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-users-update-password"></code></pre>
</div>
<form id="form-PUTapi-users-update-password" data-method="PUT" data-path="api/users/update-password" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-users-update-password', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-users-update-password" onclick="tryItOut('PUTapi-users-update-password');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-users-update-password" onclick="cancelTryOut('PUTapi-users-update-password');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-users-update-password" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/users/update-password</code></b>
</p>
<p>
<label id="auth-PUTapi-users-update-password" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-users-update-password" data-component="header"></label>
</p>
</form>


## update avatar image.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/users/upload-image" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/upload-image"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-POSTapi-users-upload-image" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-users-upload-image"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-users-upload-image"></code></pre>
</div>
<div id="execution-error-POSTapi-users-upload-image" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-users-upload-image"></code></pre>
</div>
<form id="form-POSTapi-users-upload-image" data-method="POST" data-path="api/users/upload-image" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-users-upload-image', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-users-upload-image" onclick="tryItOut('POSTapi-users-upload-image');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-users-upload-image" onclick="cancelTryOut('POSTapi-users-upload-image');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-users-upload-image" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/users/upload-image</code></b>
</p>
<p>
<label id="auth-POSTapi-users-upload-image" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-users-upload-image" data-component="header"></label>
</p>
</form>


## delete avatar image.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/users/avatar" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/avatar"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-DELETEapi-users-avatar" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-users-avatar"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-users-avatar"></code></pre>
</div>
<div id="execution-error-DELETEapi-users-avatar" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-users-avatar"></code></pre>
</div>
<form id="form-DELETEapi-users-avatar" data-method="DELETE" data-path="api/users/avatar" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-users-avatar', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-users-avatar" onclick="tryItOut('DELETEapi-users-avatar');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-users-avatar" onclick="cancelTryOut('DELETEapi-users-avatar');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-users-avatar" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/users/avatar</code></b>
</p>
<p>
<label id="auth-DELETEapi-users-avatar" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-users-avatar" data-component="header"></label>
</p>
</form>


## remove google account.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/users/google" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/google"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-DELETEapi-users-google" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-users-google"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-users-google"></code></pre>
</div>
<div id="execution-error-DELETEapi-users-google" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-users-google"></code></pre>
</div>
<form id="form-DELETEapi-users-google" data-method="DELETE" data-path="api/users/google" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-users-google', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-users-google" onclick="tryItOut('DELETEapi-users-google');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-users-google" onclick="cancelTryOut('DELETEapi-users-google');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-users-google" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/users/google</code></b>
</p>
<p>
<label id="auth-DELETEapi-users-google" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-users-google" data-component="header"></label>
</p>
</form>


## remove facebook account.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/users/facebook" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/facebook"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "DELETE",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-DELETEapi-users-facebook" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-users-facebook"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-users-facebook"></code></pre>
</div>
<div id="execution-error-DELETEapi-users-facebook" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-users-facebook"></code></pre>
</div>
<form id="form-DELETEapi-users-facebook" data-method="DELETE" data-path="api/users/facebook" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-users-facebook', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-users-facebook" onclick="tryItOut('DELETEapi-users-facebook');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-users-facebook" onclick="cancelTryOut('DELETEapi-users-facebook');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-users-facebook" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/users/facebook</code></b>
</p>
<p>
<label id="auth-DELETEapi-users-facebook" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-users-facebook" data-component="header"></label>
</p>
</form>


## add google account.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/users/google" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/google"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-POSTapi-users-google" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-users-google"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-users-google"></code></pre>
</div>
<div id="execution-error-POSTapi-users-google" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-users-google"></code></pre>
</div>
<form id="form-POSTapi-users-google" data-method="POST" data-path="api/users/google" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-users-google', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-users-google" onclick="tryItOut('POSTapi-users-google');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-users-google" onclick="cancelTryOut('POSTapi-users-google');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-users-google" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/users/google</code></b>
</p>
<p>
<label id="auth-POSTapi-users-google" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-users-google" data-component="header"></label>
</p>
</form>


## add facebook account.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/users/facebook" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/users/facebook"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "POST",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-POSTapi-users-facebook" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-users-facebook"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-users-facebook"></code></pre>
</div>
<div id="execution-error-POSTapi-users-facebook" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-users-facebook"></code></pre>
</div>
<form id="form-POSTapi-users-facebook" data-method="POST" data-path="api/users/facebook" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-users-facebook', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-users-facebook" onclick="tryItOut('POSTapi-users-facebook');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-users-facebook" onclick="cancelTryOut('POSTapi-users-facebook');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-users-facebook" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/users/facebook</code></b>
</p>
<p>
<label id="auth-POSTapi-users-facebook" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-users-facebook" data-component="header"></label>
</p>
</form>



