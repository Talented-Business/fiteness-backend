# Level Test   

APIs for managing level test

## search level tests.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/levelTests" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/levelTests"
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
<div id="execution-results-GETapi-levelTests" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-levelTests"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-levelTests"></code></pre>
</div>
<div id="execution-error-GETapi-levelTests" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-levelTests"></code></pre>
</div>
<form id="form-GETapi-levelTests" data-method="GET" data-path="api/levelTests" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-levelTests', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-levelTests" onclick="tryItOut('GETapi-levelTests');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-levelTests" onclick="cancelTryOut('GETapi-levelTests');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-levelTests" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/levelTests</code></b>
</p>
<p>
<label id="auth-GETapi-levelTests" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-levelTests" data-component="header"></label>
</p>
</form>


## create a level test.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/levelTests" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/levelTests"
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
<div id="execution-results-POSTapi-levelTests" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-levelTests"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-levelTests"></code></pre>
</div>
<div id="execution-error-POSTapi-levelTests" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-levelTests"></code></pre>
</div>
<form id="form-POSTapi-levelTests" data-method="POST" data-path="api/levelTests" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-levelTests', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-levelTests" onclick="tryItOut('POSTapi-levelTests');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-levelTests" onclick="cancelTryOut('POSTapi-levelTests');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-levelTests" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/levelTests</code></b>
</p>
<p>
<label id="auth-POSTapi-levelTests" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-levelTests" data-component="header"></label>
</p>
</form>


## update a level test.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X PUT \
    "http://127.0.0.4/api/levelTests/nisi" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/levelTests/nisi"
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
<div id="execution-results-PUTapi-levelTests--levelTest-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-levelTests--levelTest-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-levelTests--levelTest-"></code></pre>
</div>
<div id="execution-error-PUTapi-levelTests--levelTest-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-levelTests--levelTest-"></code></pre>
</div>
<form id="form-PUTapi-levelTests--levelTest-" data-method="PUT" data-path="api/levelTests/{levelTest}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-levelTests--levelTest-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-levelTests--levelTest-" onclick="tryItOut('PUTapi-levelTests--levelTest-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-levelTests--levelTest-" onclick="cancelTryOut('PUTapi-levelTests--levelTest-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-levelTests--levelTest-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/levelTests/{levelTest}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/levelTests/{levelTest}</code></b>
</p>
<p>
<label id="auth-PUTapi-levelTests--levelTest-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-levelTests--levelTest-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>levelTest</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="levelTest" data-endpoint="PUTapi-levelTests--levelTest-" data-component="url" required  hidden>
<br>
</p>
</form>


## delete a level test.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/levelTests/a" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/levelTests/a"
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
<div id="execution-results-DELETEapi-levelTests--levelTest-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-levelTests--levelTest-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-levelTests--levelTest-"></code></pre>
</div>
<div id="execution-error-DELETEapi-levelTests--levelTest-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-levelTests--levelTest-"></code></pre>
</div>
<form id="form-DELETEapi-levelTests--levelTest-" data-method="DELETE" data-path="api/levelTests/{levelTest}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-levelTests--levelTest-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-levelTests--levelTest-" onclick="tryItOut('DELETEapi-levelTests--levelTest-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-levelTests--levelTest-" onclick="cancelTryOut('DELETEapi-levelTests--levelTest-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-levelTests--levelTest-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/levelTests/{levelTest}</code></b>
</p>
<p>
<label id="auth-DELETEapi-levelTests--levelTest-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-levelTests--levelTest-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>levelTest</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="levelTest" data-endpoint="DELETEapi-levelTests--levelTest-" data-component="url" required  hidden>
<br>
</p>
</form>



