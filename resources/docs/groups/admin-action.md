# Admin Action

APIs for managing  admin actions

## create a admin action.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/admin-actions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/admin-actions"
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
<div id="execution-results-POSTapi-admin-actions" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-admin-actions"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-admin-actions"></code></pre>
</div>
<div id="execution-error-POSTapi-admin-actions" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-admin-actions"></code></pre>
</div>
<form id="form-POSTapi-admin-actions" data-method="POST" data-path="api/admin-actions" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-admin-actions', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-admin-actions" onclick="tryItOut('POSTapi-admin-actions');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-admin-actions" onclick="cancelTryOut('POSTapi-admin-actions');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-admin-actions" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/admin-actions</code></b>
</p>
<p>
<label id="auth-POSTapi-admin-actions" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-admin-actions" data-component="header"></label>
</p>
</form>



