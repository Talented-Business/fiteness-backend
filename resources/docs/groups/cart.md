# Cart

APIs for managing  cart

## inside cart

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/cart/inside" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/cart/inside"
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
<div id="execution-results-POSTapi-cart-inside" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-cart-inside"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-cart-inside"></code></pre>
</div>
<div id="execution-error-POSTapi-cart-inside" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-cart-inside"></code></pre>
</div>
<form id="form-POSTapi-cart-inside" data-method="POST" data-path="api/cart/inside" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-cart-inside', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-cart-inside" onclick="tryItOut('POSTapi-cart-inside');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-cart-inside" onclick="cancelTryOut('POSTapi-cart-inside');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-cart-inside" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/cart/inside</code></b>
</p>
<p>
<label id="auth-POSTapi-cart-inside" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-cart-inside" data-component="header"></label>
</p>
</form>


## outside cart.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/cart/outside" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/cart/outside"
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
<div id="execution-results-POSTapi-cart-outside" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-cart-outside"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-cart-outside"></code></pre>
</div>
<div id="execution-error-POSTapi-cart-outside" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-cart-outside"></code></pre>
</div>
<form id="form-POSTapi-cart-outside" data-method="POST" data-path="api/cart/outside" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-cart-outside', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-cart-outside" onclick="tryItOut('POSTapi-cart-outside');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-cart-outside" onclick="cancelTryOut('POSTapi-cart-outside');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-cart-outside" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/cart/outside</code></b>
</p>
<p>
<label id="auth-POSTapi-cart-outside" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-cart-outside" data-component="header"></label>
</p>
</form>


## show cart.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/cart/nisi" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/cart/nisi"
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
<div id="execution-results-GETapi-cart--cart-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-cart--cart-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-cart--cart-"></code></pre>
</div>
<div id="execution-error-GETapi-cart--cart-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-cart--cart-"></code></pre>
</div>
<form id="form-GETapi-cart--cart-" data-method="GET" data-path="api/cart/{cart}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-cart--cart-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-cart--cart-" onclick="tryItOut('GETapi-cart--cart-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-cart--cart-" onclick="cancelTryOut('GETapi-cart--cart-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-cart--cart-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/cart/{cart}</code></b>
</p>
<p>
<label id="auth-GETapi-cart--cart-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-cart--cart-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>cart</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="cart" data-endpoint="GETapi-cart--cart-" data-component="url" required  hidden>
<br>
</p>
</form>



