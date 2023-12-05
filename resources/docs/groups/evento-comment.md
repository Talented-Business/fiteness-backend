# Evento Comment

APIs for managing  evento comment

## search evento comments.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/evento-comments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/evento-comments"
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
<div id="execution-results-GETapi-evento-comments" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-evento-comments"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-evento-comments"></code></pre>
</div>
<div id="execution-error-GETapi-evento-comments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-evento-comments"></code></pre>
</div>
<form id="form-GETapi-evento-comments" data-method="GET" data-path="api/evento-comments" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-evento-comments', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-evento-comments" onclick="tryItOut('GETapi-evento-comments');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-evento-comments" onclick="cancelTryOut('GETapi-evento-comments');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-evento-comments" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/evento-comments</code></b>
</p>
<p>
<label id="auth-GETapi-evento-comments" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-evento-comments" data-component="header"></label>
</p>
</form>


## create a evento comment.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/evento-comments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/evento-comments"
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
<div id="execution-results-POSTapi-evento-comments" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-evento-comments"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-evento-comments"></code></pre>
</div>
<div id="execution-error-POSTapi-evento-comments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-evento-comments"></code></pre>
</div>
<form id="form-POSTapi-evento-comments" data-method="POST" data-path="api/evento-comments" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-evento-comments', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-evento-comments" onclick="tryItOut('POSTapi-evento-comments');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-evento-comments" onclick="cancelTryOut('POSTapi-evento-comments');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-evento-comments" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/evento-comments</code></b>
</p>
<p>
<label id="auth-POSTapi-evento-comments" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-evento-comments" data-component="header"></label>
</p>
</form>


## show a evento comment.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/evento-comments/labore" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/evento-comments/labore"
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
<div id="execution-results-GETapi-evento-comments--evento_comment-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-evento-comments--evento_comment-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-evento-comments--evento_comment-"></code></pre>
</div>
<div id="execution-error-GETapi-evento-comments--evento_comment-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-evento-comments--evento_comment-"></code></pre>
</div>
<form id="form-GETapi-evento-comments--evento_comment-" data-method="GET" data-path="api/evento-comments/{evento_comment}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-evento-comments--evento_comment-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-evento-comments--evento_comment-" onclick="tryItOut('GETapi-evento-comments--evento_comment-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-evento-comments--evento_comment-" onclick="cancelTryOut('GETapi-evento-comments--evento_comment-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-evento-comments--evento_comment-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/evento-comments/{evento_comment}</code></b>
</p>
<p>
<label id="auth-GETapi-evento-comments--evento_comment-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-evento-comments--evento_comment-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>evento_comment</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="evento_comment" data-endpoint="GETapi-evento-comments--evento_comment-" data-component="url" required  hidden>
<br>
</p>
</form>


## update a evento comment.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X PUT \
    "http://127.0.0.4/api/evento-comments/eligendi" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/evento-comments/eligendi"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};


fetch(url, {
    method: "PUT",
    headers,
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-PUTapi-evento-comments--evento_comment-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-evento-comments--evento_comment-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-evento-comments--evento_comment-"></code></pre>
</div>
<div id="execution-error-PUTapi-evento-comments--evento_comment-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-evento-comments--evento_comment-"></code></pre>
</div>
<form id="form-PUTapi-evento-comments--evento_comment-" data-method="PUT" data-path="api/evento-comments/{evento_comment}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-evento-comments--evento_comment-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-evento-comments--evento_comment-" onclick="tryItOut('PUTapi-evento-comments--evento_comment-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-evento-comments--evento_comment-" onclick="cancelTryOut('PUTapi-evento-comments--evento_comment-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-evento-comments--evento_comment-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/evento-comments/{evento_comment}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/evento-comments/{evento_comment}</code></b>
</p>
<p>
<label id="auth-PUTapi-evento-comments--evento_comment-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-evento-comments--evento_comment-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>evento_comment</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="evento_comment" data-endpoint="PUTapi-evento-comments--evento_comment-" data-component="url" required  hidden>
<br>
</p>
</form>


## delete a evento comment.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/evento-comments/aut" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/evento-comments/aut"
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
<div id="execution-results-DELETEapi-evento-comments--evento_comment-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-evento-comments--evento_comment-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-evento-comments--evento_comment-"></code></pre>
</div>
<div id="execution-error-DELETEapi-evento-comments--evento_comment-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-evento-comments--evento_comment-"></code></pre>
</div>
<form id="form-DELETEapi-evento-comments--evento_comment-" data-method="DELETE" data-path="api/evento-comments/{evento_comment}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-evento-comments--evento_comment-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-evento-comments--evento_comment-" onclick="tryItOut('DELETEapi-evento-comments--evento_comment-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-evento-comments--evento_comment-" onclick="cancelTryOut('DELETEapi-evento-comments--evento_comment-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-evento-comments--evento_comment-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/evento-comments/{evento_comment}</code></b>
</p>
<p>
<label id="auth-DELETEapi-evento-comments--evento_comment-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-evento-comments--evento_comment-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>evento_comment</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="evento_comment" data-endpoint="DELETEapi-evento-comments--evento_comment-" data-component="url" required  hidden>
<br>
</p>
</form>



