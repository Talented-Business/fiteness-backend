# Evento   

APIs for managing  evento

## find published eventos.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/eventos/home" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"pageSize":10,"pageNumber":20}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/eventos/home"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "pageSize": 10,
    "pageNumber": 20
}

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-GETapi-eventos-home" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-eventos-home"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-eventos-home"></code></pre>
</div>
<div id="execution-error-GETapi-eventos-home" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-eventos-home"></code></pre>
</div>
<form id="form-GETapi-eventos-home" data-method="GET" data-path="api/eventos/home" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-eventos-home', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-eventos-home" onclick="tryItOut('GETapi-eventos-home');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-eventos-home" onclick="cancelTryOut('GETapi-eventos-home');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-eventos-home" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/eventos/home</code></b>
</p>
<p>
<label id="auth-GETapi-eventos-home" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-eventos-home" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>pageSize</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="pageSize" data-endpoint="GETapi-eventos-home" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>pageNumber</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="pageNumber" data-endpoint="GETapi-eventos-home" data-component="body" required  hidden>
<br>
</p>

</form>


## get random eventos and blogs, products.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/eventos/random" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/eventos/random"
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
 "events":[{evento}],
 "news":[{blog}]
 "products":[{product}]
}
```
<div id="execution-results-GETapi-eventos-random" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-eventos-random"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-eventos-random"></code></pre>
</div>
<div id="execution-error-GETapi-eventos-random" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-eventos-random"></code></pre>
</div>
<form id="form-GETapi-eventos-random" data-method="GET" data-path="api/eventos/random" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-eventos-random', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-eventos-random" onclick="tryItOut('GETapi-eventos-random');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-eventos-random" onclick="cancelTryOut('GETapi-eventos-random');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-eventos-random" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/eventos/random</code></b>
</p>
<p>
<label id="auth-GETapi-eventos-random" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-eventos-random" data-component="header"></label>
</p>
</form>


## toggle attending on a evento.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/eventos/ipsam/toggle-attend" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/eventos/ipsam/toggle-attend"
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

{
 "event":{evento}
}
```
<div id="execution-results-POSTapi-eventos--id--toggle-attend" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-eventos--id--toggle-attend"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-eventos--id--toggle-attend"></code></pre>
</div>
<div id="execution-error-POSTapi-eventos--id--toggle-attend" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-eventos--id--toggle-attend"></code></pre>
</div>
<form id="form-POSTapi-eventos--id--toggle-attend" data-method="POST" data-path="api/eventos/{id}/toggle-attend" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-eventos--id--toggle-attend', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-eventos--id--toggle-attend" onclick="tryItOut('POSTapi-eventos--id--toggle-attend');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-eventos--id--toggle-attend" onclick="cancelTryOut('POSTapi-eventos--id--toggle-attend');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-eventos--id--toggle-attend" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/eventos/{id}/toggle-attend</code></b>
</p>
<p>
<label id="auth-POSTapi-eventos--id--toggle-attend" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-eventos--id--toggle-attend" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="POSTapi-eventos--id--toggle-attend" data-component="url" required  hidden>
<br>
</p>
</form>


## show a evento.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/eventos/4" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/eventos/4"
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
 "id":9,
 "title":"title",
 "description":"description",
 "done_date":"2022/02/04",
 "latitude":"23.1231312",
 "longitude":"-123.12312312",
 "address":"address",
 "spanish_date":"spanish_date",
 "spanish_time":"spanish_time",
 "participants":6,
 "participant": true,  //false
 "commentsCount":8,
 "comments":[{comment}] // only level1=0
}
```
<div id="execution-results-GETapi-eventos--evento-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-eventos--evento-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-eventos--evento-"></code></pre>
</div>
<div id="execution-error-GETapi-eventos--evento-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-eventos--evento-"></code></pre>
</div>
<form id="form-GETapi-eventos--evento-" data-method="GET" data-path="api/eventos/{evento}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-eventos--evento-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-eventos--evento-" onclick="tryItOut('GETapi-eventos--evento-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-eventos--evento-" onclick="cancelTryOut('GETapi-eventos--evento-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-eventos--evento-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/eventos/{evento}</code></b>
</p>
<p>
<label id="auth-GETapi-eventos--evento-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-eventos--evento-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>evento</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="evento" data-endpoint="GETapi-eventos--evento-" data-component="url" required  hidden>
<br>
</p>
</form>



