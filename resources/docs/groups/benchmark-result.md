# Benchmark result

APIs for managing  benchmark results

## get a benchmark.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/benchmarkResults/iusto/benchmark" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/benchmarkResults/iusto/benchmark"
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
<div id="execution-results-GETapi-benchmarkResults--id--benchmark" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-benchmarkResults--id--benchmark"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-benchmarkResults--id--benchmark"></code></pre>
</div>
<div id="execution-error-GETapi-benchmarkResults--id--benchmark" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-benchmarkResults--id--benchmark"></code></pre>
</div>
<form id="form-GETapi-benchmarkResults--id--benchmark" data-method="GET" data-path="api/benchmarkResults/{id}/benchmark" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-benchmarkResults--id--benchmark', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-benchmarkResults--id--benchmark" onclick="tryItOut('GETapi-benchmarkResults--id--benchmark');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-benchmarkResults--id--benchmark" onclick="cancelTryOut('GETapi-benchmarkResults--id--benchmark');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-benchmarkResults--id--benchmark" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/benchmarkResults/{id}/benchmark</code></b>
</p>
<p>
<label id="auth-GETapi-benchmarkResults--id--benchmark" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-benchmarkResults--id--benchmark" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-benchmarkResults--id--benchmark" data-component="url" required  hidden>
<br>
</p>
</form>


## get a benchmark result histories.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/benchmarkResults/history" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/benchmarkResults/history"
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
<div id="execution-results-GETapi-benchmarkResults-history" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-benchmarkResults-history"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-benchmarkResults-history"></code></pre>
</div>
<div id="execution-error-GETapi-benchmarkResults-history" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-benchmarkResults-history"></code></pre>
</div>
<form id="form-GETapi-benchmarkResults-history" data-method="GET" data-path="api/benchmarkResults/history" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-benchmarkResults-history', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-benchmarkResults-history" onclick="tryItOut('GETapi-benchmarkResults-history');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-benchmarkResults-history" onclick="cancelTryOut('GETapi-benchmarkResults-history');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-benchmarkResults-history" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/benchmarkResults/history</code></b>
</p>
<p>
<label id="auth-GETapi-benchmarkResults-history" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-benchmarkResults-history" data-component="header"></label>
</p>
</form>


## search a benchmark result.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/benchmarkResults" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/benchmarkResults"
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
<div id="execution-results-GETapi-benchmarkResults" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-benchmarkResults"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-benchmarkResults"></code></pre>
</div>
<div id="execution-error-GETapi-benchmarkResults" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-benchmarkResults"></code></pre>
</div>
<form id="form-GETapi-benchmarkResults" data-method="GET" data-path="api/benchmarkResults" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-benchmarkResults', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-benchmarkResults" onclick="tryItOut('GETapi-benchmarkResults');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-benchmarkResults" onclick="cancelTryOut('GETapi-benchmarkResults');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-benchmarkResults" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/benchmarkResults</code></b>
</p>
<p>
<label id="auth-GETapi-benchmarkResults" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-benchmarkResults" data-component="header"></label>
</p>
</form>


## create a benchmark result.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/benchmarkResults" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/benchmarkResults"
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
<div id="execution-results-POSTapi-benchmarkResults" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-benchmarkResults"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-benchmarkResults"></code></pre>
</div>
<div id="execution-error-POSTapi-benchmarkResults" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-benchmarkResults"></code></pre>
</div>
<form id="form-POSTapi-benchmarkResults" data-method="POST" data-path="api/benchmarkResults" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-benchmarkResults', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-benchmarkResults" onclick="tryItOut('POSTapi-benchmarkResults');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-benchmarkResults" onclick="cancelTryOut('POSTapi-benchmarkResults');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-benchmarkResults" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/benchmarkResults</code></b>
</p>
<p>
<label id="auth-POSTapi-benchmarkResults" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-benchmarkResults" data-component="header"></label>
</p>
</form>


## show a benchmark result.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/benchmarkResults/corrupti" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/benchmarkResults/corrupti"
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
<div id="execution-results-GETapi-benchmarkResults--benchmarkResult-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-benchmarkResults--benchmarkResult-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-benchmarkResults--benchmarkResult-"></code></pre>
</div>
<div id="execution-error-GETapi-benchmarkResults--benchmarkResult-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-benchmarkResults--benchmarkResult-"></code></pre>
</div>
<form id="form-GETapi-benchmarkResults--benchmarkResult-" data-method="GET" data-path="api/benchmarkResults/{benchmarkResult}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-benchmarkResults--benchmarkResult-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-benchmarkResults--benchmarkResult-" onclick="tryItOut('GETapi-benchmarkResults--benchmarkResult-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-benchmarkResults--benchmarkResult-" onclick="cancelTryOut('GETapi-benchmarkResults--benchmarkResult-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-benchmarkResults--benchmarkResult-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/benchmarkResults/{benchmarkResult}</code></b>
</p>
<p>
<label id="auth-GETapi-benchmarkResults--benchmarkResult-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-benchmarkResults--benchmarkResult-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>benchmarkResult</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="benchmarkResult" data-endpoint="GETapi-benchmarkResults--benchmarkResult-" data-component="url" required  hidden>
<br>
</p>
</form>


## update a benchmark result.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X PUT \
    "http://127.0.0.4/api/benchmarkResults/dignissimos" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/benchmarkResults/dignissimos"
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
<div id="execution-results-PUTapi-benchmarkResults--benchmarkResult-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-benchmarkResults--benchmarkResult-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-benchmarkResults--benchmarkResult-"></code></pre>
</div>
<div id="execution-error-PUTapi-benchmarkResults--benchmarkResult-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-benchmarkResults--benchmarkResult-"></code></pre>
</div>
<form id="form-PUTapi-benchmarkResults--benchmarkResult-" data-method="PUT" data-path="api/benchmarkResults/{benchmarkResult}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-benchmarkResults--benchmarkResult-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-benchmarkResults--benchmarkResult-" onclick="tryItOut('PUTapi-benchmarkResults--benchmarkResult-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-benchmarkResults--benchmarkResult-" onclick="cancelTryOut('PUTapi-benchmarkResults--benchmarkResult-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-benchmarkResults--benchmarkResult-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/benchmarkResults/{benchmarkResult}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/benchmarkResults/{benchmarkResult}</code></b>
</p>
<p>
<label id="auth-PUTapi-benchmarkResults--benchmarkResult-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-benchmarkResults--benchmarkResult-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>benchmarkResult</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="benchmarkResult" data-endpoint="PUTapi-benchmarkResults--benchmarkResult-" data-component="url" required  hidden>
<br>
</p>
</form>


## delete a benchmark result.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/benchmarkResults/corporis" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/benchmarkResults/corporis"
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
<div id="execution-results-DELETEapi-benchmarkResults--benchmarkResult-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-benchmarkResults--benchmarkResult-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-benchmarkResults--benchmarkResult-"></code></pre>
</div>
<div id="execution-error-DELETEapi-benchmarkResults--benchmarkResult-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-benchmarkResults--benchmarkResult-"></code></pre>
</div>
<form id="form-DELETEapi-benchmarkResults--benchmarkResult-" data-method="DELETE" data-path="api/benchmarkResults/{benchmarkResult}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-benchmarkResults--benchmarkResult-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-benchmarkResults--benchmarkResult-" onclick="tryItOut('DELETEapi-benchmarkResults--benchmarkResult-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-benchmarkResults--benchmarkResult-" onclick="cancelTryOut('DELETEapi-benchmarkResults--benchmarkResult-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-benchmarkResults--benchmarkResult-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/benchmarkResults/{benchmarkResult}</code></b>
</p>
<p>
<label id="auth-DELETEapi-benchmarkResults--benchmarkResult-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-benchmarkResults--benchmarkResult-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>benchmarkResult</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="benchmarkResult" data-endpoint="DELETEapi-benchmarkResults--benchmarkResult-" data-component="url" required  hidden>
<br>
</p>
</form>



