# Follow
This endpoint.

## unfollow a customer.

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/follows/unfollow" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/follows/unfollow"
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

{
 "status":"ok"
 "customer":{customer}
}
```
<div id="execution-results-POSTapi-follows-unfollow" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-follows-unfollow"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-follows-unfollow"></code></pre>
</div>
<div id="execution-error-POSTapi-follows-unfollow" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-follows-unfollow"></code></pre>
</div>
<form id="form-POSTapi-follows-unfollow" data-method="POST" data-path="api/follows/unfollow" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-follows-unfollow', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-follows-unfollow" onclick="tryItOut('POSTapi-follows-unfollow');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-follows-unfollow" onclick="cancelTryOut('POSTapi-follows-unfollow');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-follows-unfollow" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/follows/unfollow</code></b>
</p>
<p>
<label id="auth-POSTapi-follows-unfollow" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-follows-unfollow" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>customer_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="customer_id" data-endpoint="POSTapi-follows-unfollow" data-component="url" required  hidden>
<br>
//customer id</p>
</form>


## accept follow request.

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/follows/12/accept" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/follows/12/accept"
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

{
 "status":"accepted"
 "customer":{customer}
}
```
<div id="execution-results-POSTapi-follows--id--accept" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-follows--id--accept"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-follows--id--accept"></code></pre>
</div>
<div id="execution-error-POSTapi-follows--id--accept" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-follows--id--accept"></code></pre>
</div>
<form id="form-POSTapi-follows--id--accept" data-method="POST" data-path="api/follows/{id}/accept" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-follows--id--accept', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-follows--id--accept" onclick="tryItOut('POSTapi-follows--id--accept');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-follows--id--accept" onclick="cancelTryOut('POSTapi-follows--id--accept');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-follows--id--accept" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/follows/{id}/accept</code></b>
</p>
<p>
<label id="auth-POSTapi-follows--id--accept" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-follows--id--accept" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-follows--id--accept" data-component="url" required  hidden>
<br>
//customer id</p>
</form>


## reject follow request.

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/follows/1/reject" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/follows/1/reject"
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

{
 "status":"rejected"
 "customer":{customer}
}
```
<div id="execution-results-POSTapi-follows--id--reject" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-follows--id--reject"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-follows--id--reject"></code></pre>
</div>
<div id="execution-error-POSTapi-follows--id--reject" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-follows--id--reject"></code></pre>
</div>
<form id="form-POSTapi-follows--id--reject" data-method="POST" data-path="api/follows/{id}/reject" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-follows--id--reject', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-follows--id--reject" onclick="tryItOut('POSTapi-follows--id--reject');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-follows--id--reject" onclick="cancelTryOut('POSTapi-follows--id--reject');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-follows--id--reject" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/follows/{id}/reject</code></b>
</p>
<p>
<label id="auth-POSTapi-follows--id--reject" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-follows--id--reject" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-follows--id--reject" data-component="url" required  hidden>
<br>
//customer id</p>
</form>


## get pending follow requests of authenticated customer.

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/follows" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/follows"
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

{
     "requests":[
         {customer}
     ]
}
```
<div id="execution-results-GETapi-follows" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-follows"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-follows"></code></pre>
</div>
<div id="execution-error-GETapi-follows" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-follows"></code></pre>
</div>
<form id="form-GETapi-follows" data-method="GET" data-path="api/follows" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-follows', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-follows" onclick="tryItOut('GETapi-follows');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-follows" onclick="cancelTryOut('GETapi-follows');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-follows" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/follows</code></b>
</p>
<p>
<label id="auth-GETapi-follows" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-follows" data-component="header"></label>
</p>
</form>


## create follow request.

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/follows" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"customer_id":6}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/follows"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 6
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
 "status":"accepted", // or pending when customer type is private
 "customer":{customer}
}
```
<div id="execution-results-POSTapi-follows" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-follows"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-follows"></code></pre>
</div>
<div id="execution-error-POSTapi-follows" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-follows"></code></pre>
</div>
<form id="form-POSTapi-follows" data-method="POST" data-path="api/follows" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-follows', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-follows" onclick="tryItOut('POSTapi-follows');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-follows" onclick="cancelTryOut('POSTapi-follows');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-follows" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/follows</code></b>
</p>
<p>
<label id="auth-POSTapi-follows" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-follows" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>customer_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="customer_id" data-endpoint="POSTapi-follows" data-component="body" required  hidden>
<br>
</p>

</form>



