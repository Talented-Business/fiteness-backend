# Endpoints


## /




> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/"
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


<div id="execution-results-GET-" hidden>
    <blockquote>Received response<span id="execution-response-status-GET-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GET-"></code></pre>
</div>
<div id="execution-error-GET-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GET-"></code></pre>
</div>
<form id="form-GET-" data-method="GET" data-path="/" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GET-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GET-" onclick="tryItOut('GET-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GET-" onclick="cancelTryOut('GET-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GET-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>/</code></b>
</p>
</form>



