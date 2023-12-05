# News

APIs for managing  news

## disable a blog.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/events/necessitatibus/disable" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/events/necessitatibus/disable"
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
<div id="execution-results-GETapi-events--id--disable" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-events--id--disable"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-events--id--disable"></code></pre>
</div>
<div id="execution-error-GETapi-events--id--disable" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-events--id--disable"></code></pre>
</div>
<form id="form-GETapi-events--id--disable" data-method="GET" data-path="api/events/{id}/disable" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-events--id--disable', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-events--id--disable" onclick="tryItOut('GETapi-events--id--disable');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-events--id--disable" onclick="cancelTryOut('GETapi-events--id--disable');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-events--id--disable" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/events/{id}/disable</code></b>
</p>
<p>
<label id="auth-GETapi-events--id--disable" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-events--id--disable" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-events--id--disable" data-component="url" required  hidden>
<br>
</p>
</form>


## restore a blog.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/events/occaecati/restore" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/events/occaecati/restore"
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
<div id="execution-results-GETapi-events--id--restore" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-events--id--restore"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-events--id--restore"></code></pre>
</div>
<div id="execution-error-GETapi-events--id--restore" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-events--id--restore"></code></pre>
</div>
<form id="form-GETapi-events--id--restore" data-method="GET" data-path="api/events/{id}/restore" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-events--id--restore', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-events--id--restore" onclick="tryItOut('GETapi-events--id--restore');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-events--id--restore" onclick="cancelTryOut('GETapi-events--id--restore');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-events--id--restore" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/events/{id}/restore</code></b>
</p>
<p>
<label id="auth-GETapi-events--id--restore" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-events--id--restore" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="GETapi-events--id--restore" data-component="url" required  hidden>
<br>
</p>
</form>


## search blogs on front.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/events/home" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/events/home"
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
<div id="execution-results-GETapi-events-home" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-events-home"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-events-home"></code></pre>
</div>
<div id="execution-error-GETapi-events-home" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-events-home"></code></pre>
</div>
<form id="form-GETapi-events-home" data-method="GET" data-path="api/events/home" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-events-home', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-events-home" onclick="tryItOut('GETapi-events-home');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-events-home" onclick="cancelTryOut('GETapi-events-home');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-events-home" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/events/home</code></b>
</p>
<p>
<label id="auth-GETapi-events-home" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-events-home" data-component="header"></label>
</p>
</form>


## recent 3 blogs.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/events/recent" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/events/recent"
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
<div id="execution-results-GETapi-events-recent" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-events-recent"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-events-recent"></code></pre>
</div>
<div id="execution-error-GETapi-events-recent" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-events-recent"></code></pre>
</div>
<form id="form-GETapi-events-recent" data-method="GET" data-path="api/events/recent" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-events-recent', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-events-recent" onclick="tryItOut('GETapi-events-recent');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-events-recent" onclick="cancelTryOut('GETapi-events-recent');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-events-recent" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/events/recent</code></b>
</p>
<p>
<label id="auth-GETapi-events-recent" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-events-recent" data-component="header"></label>
</p>
</form>


## subscribe on blog with facebook.


This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/events/subscribeWithFacebook" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/events/subscribeWithFacebook"
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
<div id="execution-results-POSTapi-events-subscribeWithFacebook" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-events-subscribeWithFacebook"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-events-subscribeWithFacebook"></code></pre>
</div>
<div id="execution-error-POSTapi-events-subscribeWithFacebook" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-events-subscribeWithFacebook"></code></pre>
</div>
<form id="form-POSTapi-events-subscribeWithFacebook" data-method="POST" data-path="api/events/subscribeWithFacebook" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-events-subscribeWithFacebook', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-events-subscribeWithFacebook" onclick="tryItOut('POSTapi-events-subscribeWithFacebook');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-events-subscribeWithFacebook" onclick="cancelTryOut('POSTapi-events-subscribeWithFacebook');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-events-subscribeWithFacebook" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/events/subscribeWithFacebook</code></b>
</p>
</form>


## subscribe on blog with googl.


This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/events/subscribeWithGoogle" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/events/subscribeWithGoogle"
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
<div id="execution-results-POSTapi-events-subscribeWithGoogle" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-events-subscribeWithGoogle"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-events-subscribeWithGoogle"></code></pre>
</div>
<div id="execution-error-POSTapi-events-subscribeWithGoogle" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-events-subscribeWithGoogle"></code></pre>
</div>
<form id="form-POSTapi-events-subscribeWithGoogle" data-method="POST" data-path="api/events/subscribeWithGoogle" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-events-subscribeWithGoogle', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-events-subscribeWithGoogle" onclick="tryItOut('POSTapi-events-subscribeWithGoogle');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-events-subscribeWithGoogle" onclick="cancelTryOut('POSTapi-events-subscribeWithGoogle');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-events-subscribeWithGoogle" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/events/subscribeWithGoogle</code></b>
</p>
</form>


## subscribe on blog.


This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/events/subscribe" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/events/subscribe"
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
<div id="execution-results-POSTapi-events-subscribe" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-events-subscribe"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-events-subscribe"></code></pre>
</div>
<div id="execution-error-POSTapi-events-subscribe" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-events-subscribe"></code></pre>
</div>
<form id="form-POSTapi-events-subscribe" data-method="POST" data-path="api/events/subscribe" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-events-subscribe', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-events-subscribe" onclick="tryItOut('POSTapi-events-subscribe');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-events-subscribe" onclick="cancelTryOut('POSTapi-events-subscribe');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-events-subscribe" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/events/subscribe</code></b>
</p>
</form>


## show a blog.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/events/iusto" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/events/iusto"
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
<div id="execution-results-GETapi-events--event-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-events--event-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-events--event-"></code></pre>
</div>
<div id="execution-error-GETapi-events--event-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-events--event-"></code></pre>
</div>
<form id="form-GETapi-events--event-" data-method="GET" data-path="api/events/{event}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-events--event-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-events--event-" onclick="tryItOut('GETapi-events--event-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-events--event-" onclick="cancelTryOut('GETapi-events--event-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-events--event-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/events/{event}</code></b>
</p>
<p>
<label id="auth-GETapi-events--event-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-events--event-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>event</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="event" data-endpoint="GETapi-events--event-" data-component="url" required  hidden>
<br>
</p>
</form>



