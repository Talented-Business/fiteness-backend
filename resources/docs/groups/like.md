# Like   

APIs for managing  likes for post and comment on social part

## get liker(customer) list for an activity 10 per page.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/likes?activity_id=19&pageNumber=7" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/likes"
);

let params = {
    "activity_id": "19",
    "pageNumber": "7",
};
Object.keys(params)
    .forEach(key => url.searchParams.append(key, params[key]));

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
[
 {
 "id"=>1,
 "activity_id"=>1,
 "customer_id"=>1,
 "customer"=>{
     "first_name"=>'first',
     "last_name"=>'last',
     "avatarUrls"=>[]
 }
 }]
}
```
<div id="execution-results-GETapi-likes" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-likes"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-likes"></code></pre>
</div>
<div id="execution-error-GETapi-likes" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-likes"></code></pre>
</div>
<form id="form-GETapi-likes" data-method="GET" data-path="api/likes" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-likes', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-likes" onclick="tryItOut('GETapi-likes');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-likes" onclick="cancelTryOut('GETapi-likes');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-likes" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/likes</code></b>
</p>
<p>
<label id="auth-GETapi-likes" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-likes" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>activity_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="activity_id" data-endpoint="GETapi-likes" data-component="query" required  hidden>
<br>
</p>
<p>
<b><code>pageNumber</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="pageNumber" data-endpoint="GETapi-likes" data-component="query"  hidden>
<br>
</p>
</form>


## create a like.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/likes" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"activity_id":3}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/likes"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "activity_id": 3
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
<div id="execution-results-POSTapi-likes" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-likes"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-likes"></code></pre>
</div>
<div id="execution-error-POSTapi-likes" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-likes"></code></pre>
</div>
<form id="form-POSTapi-likes" data-method="POST" data-path="api/likes" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-likes', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-likes" onclick="tryItOut('POSTapi-likes');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-likes" onclick="cancelTryOut('POSTapi-likes');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-likes" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/likes</code></b>
</p>
<p>
<label id="auth-POSTapi-likes" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-likes" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>activity_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="activity_id" data-endpoint="POSTapi-likes" data-component="body" required  hidden>
<br>
</p>

</form>


## delete a like.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/likes/nam" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/likes/nam"
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
<div id="execution-results-DELETEapi-likes--like-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-likes--like-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-likes--like-"></code></pre>
</div>
<div id="execution-error-DELETEapi-likes--like-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-likes--like-"></code></pre>
</div>
<form id="form-DELETEapi-likes--like-" data-method="DELETE" data-path="api/likes/{like}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-likes--like-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-likes--like-" onclick="tryItOut('DELETEapi-likes--like-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-likes--like-" onclick="cancelTryOut('DELETEapi-likes--like-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-likes--like-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/likes/{like}</code></b>
</p>
<p>
<label id="auth-DELETEapi-likes--like-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-likes--like-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>like</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="like" data-endpoint="DELETEapi-likes--like-" data-component="url" required  hidden>
<br>
</p>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="DELETEapi-likes--like-" data-component="url" required  hidden>
<br>
</p>
</form>



