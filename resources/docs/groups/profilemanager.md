# ProfileManager   

APIs for managing  likes for post and comment on social part

## get customers with profileManager Role.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/profile-managers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/profile-managers"
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
[{customer}]
}
```
<div id="execution-results-GETapi-profile-managers" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-profile-managers"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-profile-managers"></code></pre>
</div>
<div id="execution-error-GETapi-profile-managers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-profile-managers"></code></pre>
</div>
<form id="form-GETapi-profile-managers" data-method="GET" data-path="api/profile-managers" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-profile-managers', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-profile-managers" onclick="tryItOut('GETapi-profile-managers');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-profile-managers" onclick="cancelTryOut('GETapi-profile-managers');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-profile-managers" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/profile-managers</code></b>
</p>
<p>
<label id="auth-GETapi-profile-managers" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-profile-managers" data-component="header"></label>
</p>
</form>


## create a profileManager.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/profile-managers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"activity_id":1}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/profile-managers"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "activity_id": 1
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
<div id="execution-results-POSTapi-profile-managers" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-profile-managers"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-profile-managers"></code></pre>
</div>
<div id="execution-error-POSTapi-profile-managers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-profile-managers"></code></pre>
</div>
<form id="form-POSTapi-profile-managers" data-method="POST" data-path="api/profile-managers" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-profile-managers', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-profile-managers" onclick="tryItOut('POSTapi-profile-managers');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-profile-managers" onclick="cancelTryOut('POSTapi-profile-managers');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-profile-managers" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/profile-managers</code></b>
</p>
<p>
<label id="auth-POSTapi-profile-managers" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-profile-managers" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>activity_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="activity_id" data-endpoint="POSTapi-profile-managers" data-component="body" required  hidden>
<br>
</p>

</form>


## delete a profileManager.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/profile-managers/aliquid" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/profile-managers/aliquid"
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
<div id="execution-results-DELETEapi-profile-managers--profile_manager-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-profile-managers--profile_manager-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-profile-managers--profile_manager-"></code></pre>
</div>
<div id="execution-error-DELETEapi-profile-managers--profile_manager-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-profile-managers--profile_manager-"></code></pre>
</div>
<form id="form-DELETEapi-profile-managers--profile_manager-" data-method="DELETE" data-path="api/profile-managers/{profile_manager}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-profile-managers--profile_manager-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-profile-managers--profile_manager-" onclick="tryItOut('DELETEapi-profile-managers--profile_manager-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-profile-managers--profile_manager-" onclick="cancelTryOut('DELETEapi-profile-managers--profile_manager-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-profile-managers--profile_manager-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/profile-managers/{profile_manager}</code></b>
</p>
<p>
<label id="auth-DELETEapi-profile-managers--profile_manager-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-profile-managers--profile_manager-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>profile_manager</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="profile_manager" data-endpoint="DELETEapi-profile-managers--profile_manager-" data-component="url" required  hidden>
<br>
</p>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="DELETEapi-profile-managers--profile_manager-" data-component="url" required  hidden>
<br>
</p>
</form>



