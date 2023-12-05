# Report   

APIs for managing  report

## get customer report.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/reports/customers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/reports/customers"
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
<div id="execution-results-GETapi-reports-customers" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-reports-customers"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-reports-customers"></code></pre>
</div>
<div id="execution-error-GETapi-reports-customers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-reports-customers"></code></pre>
</div>
<form id="form-GETapi-reports-customers" data-method="GET" data-path="api/reports/customers" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-reports-customers', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-reports-customers" onclick="tryItOut('GETapi-reports-customers');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-reports-customers" onclick="cancelTryOut('GETapi-reports-customers');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-reports-customers" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/reports/customers</code></b>
</p>
<p>
<label id="auth-GETapi-reports-customers" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-reports-customers" data-component="header"></label>
</p>
</form>


## export customers.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/reports/export-customers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/reports/export-customers"
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
<div id="execution-results-GETapi-reports-export-customers" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-reports-export-customers"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-reports-export-customers"></code></pre>
</div>
<div id="execution-error-GETapi-reports-export-customers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-reports-export-customers"></code></pre>
</div>
<form id="form-GETapi-reports-export-customers" data-method="GET" data-path="api/reports/export-customers" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-reports-export-customers', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-reports-export-customers" onclick="tryItOut('GETapi-reports-export-customers');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-reports-export-customers" onclick="cancelTryOut('GETapi-reports-export-customers');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-reports-export-customers" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/reports/export-customers</code></b>
</p>
<p>
<label id="auth-GETapi-reports-export-customers" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-reports-export-customers" data-component="header"></label>
</p>
</form>


## export usage.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/reports/export-usage" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/reports/export-usage"
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
<div id="execution-results-GETapi-reports-export-usage" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-reports-export-usage"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-reports-export-usage"></code></pre>
</div>
<div id="execution-error-GETapi-reports-export-usage" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-reports-export-usage"></code></pre>
</div>
<form id="form-GETapi-reports-export-usage" data-method="GET" data-path="api/reports/export-usage" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-reports-export-usage', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-reports-export-usage" onclick="tryItOut('GETapi-reports-export-usage');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-reports-export-usage" onclick="cancelTryOut('GETapi-reports-export-usage');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-reports-export-usage" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/reports/export-usage</code></b>
</p>
<p>
<label id="auth-GETapi-reports-export-usage" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-reports-export-usage" data-component="header"></label>
</p>
</form>


## get customer workouts.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/reports/customer-workouts" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/reports/customer-workouts"
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
<div id="execution-results-GETapi-reports-customer-workouts" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-reports-customer-workouts"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-reports-customer-workouts"></code></pre>
</div>
<div id="execution-error-GETapi-reports-customer-workouts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-reports-customer-workouts"></code></pre>
</div>
<form id="form-GETapi-reports-customer-workouts" data-method="GET" data-path="api/reports/customer-workouts" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-reports-customer-workouts', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-reports-customer-workouts" onclick="tryItOut('GETapi-reports-customer-workouts');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-reports-customer-workouts" onclick="cancelTryOut('GETapi-reports-customer-workouts');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-reports-customer-workouts" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/reports/customer-workouts</code></b>
</p>
<p>
<label id="auth-GETapi-reports-customer-workouts" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-reports-customer-workouts" data-component="header"></label>
</p>
</form>


## get customer workouts.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/reports/customer-workouts-range?range=aut&gender=repellendus" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/reports/customer-workouts-range"
);

let params = {
    "range": "aut",
    "gender": "repellendus",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

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
<div id="execution-results-GETapi-reports-customer-workouts-range" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-reports-customer-workouts-range"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-reports-customer-workouts-range"></code></pre>
</div>
<div id="execution-error-GETapi-reports-customer-workouts-range" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-reports-customer-workouts-range"></code></pre>
</div>
<form id="form-GETapi-reports-customer-workouts-range" data-method="GET" data-path="api/reports/customer-workouts-range" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-reports-customer-workouts-range', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-reports-customer-workouts-range" onclick="tryItOut('GETapi-reports-customer-workouts-range');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-reports-customer-workouts-range" onclick="cancelTryOut('GETapi-reports-customer-workouts-range');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-reports-customer-workouts-range" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/reports/customer-workouts-range</code></b>
</p>
<p>
<label id="auth-GETapi-reports-customer-workouts-range" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-reports-customer-workouts-range" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>range</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="range" data-endpoint="GETapi-reports-customer-workouts-range" data-component="query" required  hidden>
<br>
// all , current, last</p>
<p>
<b><code>gender</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="gender" data-endpoint="GETapi-reports-customer-workouts-range" data-component="query" required  hidden>
<br>
// all , Male, Female, MaleMaster, FemaleMaster</p>
</form>


## export subscriptions.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/reports/export-subscriptions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/reports/export-subscriptions"
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
<div id="execution-results-GETapi-reports-export-subscriptions" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-reports-export-subscriptions"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-reports-export-subscriptions"></code></pre>
</div>
<div id="execution-error-GETapi-reports-export-subscriptions" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-reports-export-subscriptions"></code></pre>
</div>
<form id="form-GETapi-reports-export-subscriptions" data-method="GET" data-path="api/reports/export-subscriptions" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-reports-export-subscriptions', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-reports-export-subscriptions" onclick="tryItOut('GETapi-reports-export-subscriptions');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-reports-export-subscriptions" onclick="cancelTryOut('GETapi-reports-export-subscriptions');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-reports-export-subscriptions" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/reports/export-subscriptions</code></b>
</p>
<p>
<label id="auth-GETapi-reports-export-subscriptions" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-reports-export-subscriptions" data-component="header"></label>
</p>
</form>



