# Shortcode    on workout

APIs for managing  shortcodes on workout

## get active shortcode list.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/shortcodes/all" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/shortcodes/all"
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
<div id="execution-results-GETapi-shortcodes-all" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-shortcodes-all"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-shortcodes-all"></code></pre>
</div>
<div id="execution-error-GETapi-shortcodes-all" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-shortcodes-all"></code></pre>
</div>
<form id="form-GETapi-shortcodes-all" data-method="GET" data-path="api/shortcodes/all" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-shortcodes-all', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-shortcodes-all" onclick="tryItOut('GETapi-shortcodes-all');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-shortcodes-all" onclick="cancelTryOut('GETapi-shortcodes-all');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-shortcodes-all" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/shortcodes/all</code></b>
</p>
<p>
<label id="auth-GETapi-shortcodes-all" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-shortcodes-all" data-component="header"></label>
</p>
</form>



