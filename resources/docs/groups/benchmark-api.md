# Benchmark API

APIs for managing  benchmark

## get published benchmarks.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/benchmarks/published" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/benchmarks/published"
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
<div id="execution-results-GETapi-benchmarks-published" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-benchmarks-published"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-benchmarks-published"></code></pre>
</div>
<div id="execution-error-GETapi-benchmarks-published" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-benchmarks-published"></code></pre>
</div>
<form id="form-GETapi-benchmarks-published" data-method="GET" data-path="api/benchmarks/published" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-benchmarks-published', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-benchmarks-published" onclick="tryItOut('GETapi-benchmarks-published');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-benchmarks-published" onclick="cancelTryOut('GETapi-benchmarks-published');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-benchmarks-published" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/benchmarks/published</code></b>
</p>
<p>
<label id="auth-GETapi-benchmarks-published" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-benchmarks-published" data-component="header"></label>
</p>
</form>


## show a benchmark.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/benchmarks/qui" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/benchmarks/qui"
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
<div id="execution-results-GETapi-benchmarks--benchmark-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-benchmarks--benchmark-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-benchmarks--benchmark-"></code></pre>
</div>
<div id="execution-error-GETapi-benchmarks--benchmark-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-benchmarks--benchmark-"></code></pre>
</div>
<form id="form-GETapi-benchmarks--benchmark-" data-method="GET" data-path="api/benchmarks/{benchmark}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-benchmarks--benchmark-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-benchmarks--benchmark-" onclick="tryItOut('GETapi-benchmarks--benchmark-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-benchmarks--benchmark-" onclick="cancelTryOut('GETapi-benchmarks--benchmark-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-benchmarks--benchmark-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/benchmarks/{benchmark}</code></b>
</p>
<p>
<label id="auth-GETapi-benchmarks--benchmark-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-benchmarks--benchmark-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>benchmark</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="benchmark" data-endpoint="GETapi-benchmarks--benchmark-" data-component="url" required  hidden>
<br>
</p>
</form>



