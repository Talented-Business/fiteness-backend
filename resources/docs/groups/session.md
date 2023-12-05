# Session   

APIs for managing  session

## inside

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/sessions/inside" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/sessions/inside"
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
<div id="execution-results-POSTapi-sessions-inside" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-sessions-inside"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sessions-inside"></code></pre>
</div>
<div id="execution-error-POSTapi-sessions-inside" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sessions-inside"></code></pre>
</div>
<form id="form-POSTapi-sessions-inside" data-method="POST" data-path="api/sessions/inside" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-sessions-inside', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-sessions-inside" onclick="tryItOut('POSTapi-sessions-inside');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-sessions-inside" onclick="cancelTryOut('POSTapi-sessions-inside');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-sessions-inside" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/sessions/inside</code></b>
</p>
<p>
<label id="auth-POSTapi-sessions-inside" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-sessions-inside" data-component="header"></label>
</p>
</form>


## outside.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/sessions/outside" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/sessions/outside"
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
<div id="execution-results-POSTapi-sessions-outside" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-sessions-outside"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-sessions-outside"></code></pre>
</div>
<div id="execution-error-POSTapi-sessions-outside" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-sessions-outside"></code></pre>
</div>
<form id="form-POSTapi-sessions-outside" data-method="POST" data-path="api/sessions/outside" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-sessions-outside', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-sessions-outside" onclick="tryItOut('POSTapi-sessions-outside');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-sessions-outside" onclick="cancelTryOut('POSTapi-sessions-outside');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-sessions-outside" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/sessions/outside</code></b>
</p>
<p>
<label id="auth-POSTapi-sessions-outside" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-sessions-outside" data-component="header"></label>
</p>
</form>



