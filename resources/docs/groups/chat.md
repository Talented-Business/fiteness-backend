# Chat

APIs for managing  chat

## verify.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/chat/verify" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/chat/verify"
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
<div id="execution-results-GETapi-chat-verify" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-chat-verify"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-chat-verify"></code></pre>
</div>
<div id="execution-error-GETapi-chat-verify" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-chat-verify"></code></pre>
</div>
<form id="form-GETapi-chat-verify" data-method="GET" data-path="api/chat/verify" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-chat-verify', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-chat-verify" onclick="tryItOut('GETapi-chat-verify');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-chat-verify" onclick="cancelTryOut('GETapi-chat-verify');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-chat-verify" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/chat/verify</code></b>
</p>
<p>
<label id="auth-GETapi-chat-verify" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-chat-verify" data-component="header"></label>
</p>
</form>


## verify from local.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/chat/verify-local" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/chat/verify-local"
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
<div id="execution-results-GETapi-chat-verify-local" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-chat-verify-local"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-chat-verify-local"></code></pre>
</div>
<div id="execution-error-GETapi-chat-verify-local" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-chat-verify-local"></code></pre>
</div>
<form id="form-GETapi-chat-verify-local" data-method="GET" data-path="api/chat/verify-local" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-chat-verify-local', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-chat-verify-local" onclick="tryItOut('GETapi-chat-verify-local');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-chat-verify-local" onclick="cancelTryOut('GETapi-chat-verify-local');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-chat-verify-local" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/chat/verify-local</code></b>
</p>
<p>
<label id="auth-GETapi-chat-verify-local" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-chat-verify-local" data-component="header"></label>
</p>
</form>


## save user&#039;s chat id.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/chat/user-id" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"chat_id":15}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/chat/user-id"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "chat_id": 15
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
<div id="execution-results-POSTapi-chat-user-id" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-chat-user-id"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-chat-user-id"></code></pre>
</div>
<div id="execution-error-POSTapi-chat-user-id" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-chat-user-id"></code></pre>
</div>
<form id="form-POSTapi-chat-user-id" data-method="POST" data-path="api/chat/user-id" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-chat-user-id', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-chat-user-id" onclick="tryItOut('POSTapi-chat-user-id');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-chat-user-id" onclick="cancelTryOut('POSTapi-chat-user-id');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-chat-user-id" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/chat/user-id</code></b>
</p>
<p>
<label id="auth-POSTapi-chat-user-id" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-chat-user-id" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>chat_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="chat_id" data-endpoint="POSTapi-chat-user-id" data-component="body" required  hidden>
<br>
</p>

</form>



