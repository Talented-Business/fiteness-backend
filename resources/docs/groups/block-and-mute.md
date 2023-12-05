# Block and mute
This endpoint.

## block a customer.

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/follows/block" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"customer_id":8}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/follows/block"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 8
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
 "status":"ok",
 "customer":{customer}
}
```
<div id="execution-results-POSTapi-follows-block" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-follows-block"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-follows-block"></code></pre>
</div>
<div id="execution-error-POSTapi-follows-block" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-follows-block"></code></pre>
</div>
<form id="form-POSTapi-follows-block" data-method="POST" data-path="api/follows/block" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-follows-block', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-follows-block" onclick="tryItOut('POSTapi-follows-block');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-follows-block" onclick="cancelTryOut('POSTapi-follows-block');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-follows-block" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/follows/block</code></b>
</p>
<p>
<label id="auth-POSTapi-follows-block" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-follows-block" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>customer_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="customer_id" data-endpoint="POSTapi-follows-block" data-component="body" required  hidden>
<br>
</p>

</form>


## unblock a customer.

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/follows/unblock" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"customer_id":2}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/follows/unblock"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 2
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
 "status":"ok",
 "customer":{customer}
}
```
<div id="execution-results-POSTapi-follows-unblock" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-follows-unblock"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-follows-unblock"></code></pre>
</div>
<div id="execution-error-POSTapi-follows-unblock" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-follows-unblock"></code></pre>
</div>
<form id="form-POSTapi-follows-unblock" data-method="POST" data-path="api/follows/unblock" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-follows-unblock', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-follows-unblock" onclick="tryItOut('POSTapi-follows-unblock');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-follows-unblock" onclick="cancelTryOut('POSTapi-follows-unblock');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-follows-unblock" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/follows/unblock</code></b>
</p>
<p>
<label id="auth-POSTapi-follows-unblock" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-follows-unblock" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>customer_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="customer_id" data-endpoint="POSTapi-follows-unblock" data-component="body" required  hidden>
<br>
</p>

</form>


## mute a customer.

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/follows/mute" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"customer_id":2}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/follows/mute"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 2
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
 "status":"ok",
 "customer":{customer}
}
```
<div id="execution-results-POSTapi-follows-mute" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-follows-mute"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-follows-mute"></code></pre>
</div>
<div id="execution-error-POSTapi-follows-mute" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-follows-mute"></code></pre>
</div>
<form id="form-POSTapi-follows-mute" data-method="POST" data-path="api/follows/mute" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-follows-mute', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-follows-mute" onclick="tryItOut('POSTapi-follows-mute');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-follows-mute" onclick="cancelTryOut('POSTapi-follows-mute');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-follows-mute" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/follows/mute</code></b>
</p>
<p>
<label id="auth-POSTapi-follows-mute" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-follows-mute" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>customer_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="customer_id" data-endpoint="POSTapi-follows-mute" data-component="body" required  hidden>
<br>
</p>

</form>


## unmute a customer.

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/follows/unmute" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"customer_id":14}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/follows/unmute"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 14
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
 "status":"ok",
 "customer":{customer}
}
```
<div id="execution-results-POSTapi-follows-unmute" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-follows-unmute"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-follows-unmute"></code></pre>
</div>
<div id="execution-error-POSTapi-follows-unmute" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-follows-unmute"></code></pre>
</div>
<form id="form-POSTapi-follows-unmute" data-method="POST" data-path="api/follows/unmute" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-follows-unmute', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-follows-unmute" onclick="tryItOut('POSTapi-follows-unmute');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-follows-unmute" onclick="cancelTryOut('POSTapi-follows-unmute');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-follows-unmute" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/follows/unmute</code></b>
</p>
<p>
<label id="auth-POSTapi-follows-unmute" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-follows-unmute" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>customer_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="customer_id" data-endpoint="POSTapi-follows-unmute" data-component="body" required  hidden>
<br>
</p>

</form>



