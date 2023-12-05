# Survey   

APIs for managing  survey

## get current survey for me.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/surveys/me" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/surveys/me"
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
<div id="execution-results-GETapi-surveys-me" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-surveys-me"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-surveys-me"></code></pre>
</div>
<div id="execution-error-GETapi-surveys-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-surveys-me"></code></pre>
</div>
<form id="form-GETapi-surveys-me" data-method="GET" data-path="api/surveys/me" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-surveys-me', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-surveys-me" onclick="tryItOut('GETapi-surveys-me');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-surveys-me" onclick="cancelTryOut('GETapi-surveys-me');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-surveys-me" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/surveys/me</code></b>
</p>
<p>
<label id="auth-GETapi-surveys-me" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-surveys-me" data-component="header"></label>
</p>
</form>



