# Payment Token   

APIs for managing  payment tokens

## search payment tokens.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/tockens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/tockens"
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
<div id="execution-results-GETapi-tockens" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-tockens"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-tockens"></code></pre>
</div>
<div id="execution-error-GETapi-tockens" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-tockens"></code></pre>
</div>
<form id="form-GETapi-tockens" data-method="GET" data-path="api/tockens" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-tockens', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-tockens" onclick="tryItOut('GETapi-tockens');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-tockens" onclick="cancelTryOut('GETapi-tockens');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-tockens" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/tockens</code></b>
</p>
<p>
<label id="auth-GETapi-tockens" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-tockens" data-component="header"></label>
</p>
</form>


## create a payment token.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/tockens" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/tockens"
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
<div id="execution-results-POSTapi-tockens" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-tockens"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-tockens"></code></pre>
</div>
<div id="execution-error-POSTapi-tockens" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-tockens"></code></pre>
</div>
<form id="form-POSTapi-tockens" data-method="POST" data-path="api/tockens" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-tockens', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-tockens" onclick="tryItOut('POSTapi-tockens');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-tockens" onclick="cancelTryOut('POSTapi-tockens');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-tockens" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/tockens</code></b>
</p>
<p>
<label id="auth-POSTapi-tockens" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-tockens" data-component="header"></label>
</p>
</form>


## show a payment token.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/tockens/et" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/tockens/et"
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
<div id="execution-results-GETapi-tockens--tocken-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-tockens--tocken-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-tockens--tocken-"></code></pre>
</div>
<div id="execution-error-GETapi-tockens--tocken-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-tockens--tocken-"></code></pre>
</div>
<form id="form-GETapi-tockens--tocken-" data-method="GET" data-path="api/tockens/{tocken}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-tockens--tocken-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-tockens--tocken-" onclick="tryItOut('GETapi-tockens--tocken-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-tockens--tocken-" onclick="cancelTryOut('GETapi-tockens--tocken-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-tockens--tocken-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/tockens/{tocken}</code></b>
</p>
<p>
<label id="auth-GETapi-tockens--tocken-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-tockens--tocken-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>tocken</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="tocken" data-endpoint="GETapi-tockens--tocken-" data-component="url" required  hidden>
<br>
</p>
</form>


## update a payment token.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X PUT \
    "http://127.0.0.4/api/tockens/dolore" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/tockens/dolore"
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
<div id="execution-results-PUTapi-tockens--tocken-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-tockens--tocken-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-tockens--tocken-"></code></pre>
</div>
<div id="execution-error-PUTapi-tockens--tocken-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-tockens--tocken-"></code></pre>
</div>
<form id="form-PUTapi-tockens--tocken-" data-method="PUT" data-path="api/tockens/{tocken}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-tockens--tocken-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-tockens--tocken-" onclick="tryItOut('PUTapi-tockens--tocken-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-tockens--tocken-" onclick="cancelTryOut('PUTapi-tockens--tocken-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-tockens--tocken-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/tockens/{tocken}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/tockens/{tocken}</code></b>
</p>
<p>
<label id="auth-PUTapi-tockens--tocken-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-tockens--tocken-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>tocken</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="tocken" data-endpoint="PUTapi-tockens--tocken-" data-component="url" required  hidden>
<br>
</p>
</form>


## delete a payment token.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/tockens/nemo" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/tockens/nemo"
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
<div id="execution-results-DELETEapi-tockens--tocken-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-tockens--tocken-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-tockens--tocken-"></code></pre>
</div>
<div id="execution-error-DELETEapi-tockens--tocken-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-tockens--tocken-"></code></pre>
</div>
<form id="form-DELETEapi-tockens--tocken-" data-method="DELETE" data-path="api/tockens/{tocken}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-tockens--tocken-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-tockens--tocken-" onclick="tryItOut('DELETEapi-tockens--tocken-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-tockens--tocken-" onclick="cancelTryOut('DELETEapi-tockens--tocken-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-tockens--tocken-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/tockens/{tocken}</code></b>
</p>
<p>
<label id="auth-DELETEapi-tockens--tocken-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-tockens--tocken-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>tocken</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="tocken" data-endpoint="DELETEapi-tockens--tocken-" data-component="url" required  hidden>
<br>
</p>
</form>



