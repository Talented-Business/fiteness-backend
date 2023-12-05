# reporting    on social part

APIs for reporting

## create a report.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/social-reports" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/social-reports"
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
<div id="execution-results-POSTapi-social-reports" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-social-reports"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-social-reports"></code></pre>
</div>
<div id="execution-error-POSTapi-social-reports" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-social-reports"></code></pre>
</div>
<form id="form-POSTapi-social-reports" data-method="POST" data-path="api/social-reports" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-social-reports', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-social-reports" onclick="tryItOut('POSTapi-social-reports');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-social-reports" onclick="cancelTryOut('POSTapi-social-reports');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-social-reports" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/social-reports</code></b>
</p>
<p>
<label id="auth-POSTapi-social-reports" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-social-reports" data-component="header"></label>
</p>
</form>


## show a report.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/social-reports/id" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/social-reports/id"
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
<div id="execution-results-GETapi-social-reports--social_report-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-social-reports--social_report-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-social-reports--social_report-"></code></pre>
</div>
<div id="execution-error-GETapi-social-reports--social_report-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-social-reports--social_report-"></code></pre>
</div>
<form id="form-GETapi-social-reports--social_report-" data-method="GET" data-path="api/social-reports/{social_report}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-social-reports--social_report-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-social-reports--social_report-" onclick="tryItOut('GETapi-social-reports--social_report-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-social-reports--social_report-" onclick="cancelTryOut('GETapi-social-reports--social_report-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-social-reports--social_report-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/social-reports/{social_report}</code></b>
</p>
<p>
<label id="auth-GETapi-social-reports--social_report-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-social-reports--social_report-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>social_report</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="social_report" data-endpoint="GETapi-social-reports--social_report-" data-component="url" required  hidden>
<br>
</p>
</form>


## update a report.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X PUT \
    "http://127.0.0.4/api/social-reports/corporis" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/social-reports/corporis"
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
<div id="execution-results-PUTapi-social-reports--social_report-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-social-reports--social_report-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-social-reports--social_report-"></code></pre>
</div>
<div id="execution-error-PUTapi-social-reports--social_report-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-social-reports--social_report-"></code></pre>
</div>
<form id="form-PUTapi-social-reports--social_report-" data-method="PUT" data-path="api/social-reports/{social_report}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-social-reports--social_report-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-social-reports--social_report-" onclick="tryItOut('PUTapi-social-reports--social_report-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-social-reports--social_report-" onclick="cancelTryOut('PUTapi-social-reports--social_report-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-social-reports--social_report-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/social-reports/{social_report}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/social-reports/{social_report}</code></b>
</p>
<p>
<label id="auth-PUTapi-social-reports--social_report-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-social-reports--social_report-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>social_report</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="social_report" data-endpoint="PUTapi-social-reports--social_report-" data-component="url" required  hidden>
<br>
</p>
</form>


## delete a report.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/social-reports/qui" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/social-reports/qui"
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
<div id="execution-results-DELETEapi-social-reports--social_report-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-social-reports--social_report-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-social-reports--social_report-"></code></pre>
</div>
<div id="execution-error-DELETEapi-social-reports--social_report-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-social-reports--social_report-"></code></pre>
</div>
<form id="form-DELETEapi-social-reports--social_report-" data-method="DELETE" data-path="api/social-reports/{social_report}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-social-reports--social_report-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-social-reports--social_report-" onclick="tryItOut('DELETEapi-social-reports--social_report-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-social-reports--social_report-" onclick="cancelTryOut('DELETEapi-social-reports--social_report-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-social-reports--social_report-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/social-reports/{social_report}</code></b>
</p>
<p>
<label id="auth-DELETEapi-social-reports--social_report-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-social-reports--social_report-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>social_report</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="social_report" data-endpoint="DELETEapi-social-reports--social_report-" data-component="url" required  hidden>
<br>
</p>
</form>



