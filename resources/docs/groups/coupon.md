# Coupon

APIs for managing  coupon

## check the validation of a coupon on renewal.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/coupons/check" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/coupons/check"
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
<div id="execution-results-POSTapi-coupons-check" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-coupons-check"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-coupons-check"></code></pre>
</div>
<div id="execution-error-POSTapi-coupons-check" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-coupons-check"></code></pre>
</div>
<form id="form-POSTapi-coupons-check" data-method="POST" data-path="api/coupons/check" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-coupons-check', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-coupons-check" onclick="tryItOut('POSTapi-coupons-check');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-coupons-check" onclick="cancelTryOut('POSTapi-coupons-check');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-coupons-check" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/coupons/check</code></b>
</p>
<p>
<label id="auth-POSTapi-coupons-check" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-coupons-check" data-component="header"></label>
</p>
</form>


## generate first pay on a coupon.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/coupons/generateFirstPay" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/coupons/generateFirstPay"
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
<div id="execution-results-POSTapi-coupons-generateFirstPay" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-coupons-generateFirstPay"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-coupons-generateFirstPay"></code></pre>
</div>
<div id="execution-error-POSTapi-coupons-generateFirstPay" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-coupons-generateFirstPay"></code></pre>
</div>
<form id="form-POSTapi-coupons-generateFirstPay" data-method="POST" data-path="api/coupons/generateFirstPay" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-coupons-generateFirstPay', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-coupons-generateFirstPay" onclick="tryItOut('POSTapi-coupons-generateFirstPay');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-coupons-generateFirstPay" onclick="cancelTryOut('POSTapi-coupons-generateFirstPay');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-coupons-generateFirstPay" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/coupons/generateFirstPay</code></b>
</p>
<p>
<label id="auth-POSTapi-coupons-generateFirstPay" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-coupons-generateFirstPay" data-component="header"></label>
</p>
</form>


## show  a private coupon.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/coupons/private" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/coupons/private"
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
<div id="execution-results-POSTapi-coupons-private" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-coupons-private"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-coupons-private"></code></pre>
</div>
<div id="execution-error-POSTapi-coupons-private" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-coupons-private"></code></pre>
</div>
<form id="form-POSTapi-coupons-private" data-method="POST" data-path="api/coupons/private" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-coupons-private', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-coupons-private" onclick="tryItOut('POSTapi-coupons-private');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-coupons-private" onclick="cancelTryOut('POSTapi-coupons-private');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-coupons-private" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/coupons/private</code></b>
</p>
<p>
<label id="auth-POSTapi-coupons-private" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-coupons-private" data-component="header"></label>
</p>
</form>


## create a renewal coupon for autenticated customer.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/coupons/subscription" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/coupons/subscription"
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
<div id="execution-results-POSTapi-coupons-subscription" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-coupons-subscription"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-coupons-subscription"></code></pre>
</div>
<div id="execution-error-POSTapi-coupons-subscription" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-coupons-subscription"></code></pre>
</div>
<form id="form-POSTapi-coupons-subscription" data-method="POST" data-path="api/coupons/subscription" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-coupons-subscription', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-coupons-subscription" onclick="tryItOut('POSTapi-coupons-subscription');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-coupons-subscription" onclick="cancelTryOut('POSTapi-coupons-subscription');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-coupons-subscription" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/coupons/subscription</code></b>
</p>
<p>
<label id="auth-POSTapi-coupons-subscription" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-coupons-subscription" data-component="header"></label>
</p>
</form>


## show a public coupon including invitation code coupon.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/coupons/public" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/coupons/public"
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
<div id="execution-results-POSTapi-coupons-public" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-coupons-public"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-coupons-public"></code></pre>
</div>
<div id="execution-error-POSTapi-coupons-public" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-coupons-public"></code></pre>
</div>
<form id="form-POSTapi-coupons-public" data-method="POST" data-path="api/coupons/public" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-coupons-public', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-coupons-public" onclick="tryItOut('POSTapi-coupons-public');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-coupons-public" onclick="cancelTryOut('POSTapi-coupons-public');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-coupons-public" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/coupons/public</code></b>
</p>
<p>
<label id="auth-POSTapi-coupons-public" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-coupons-public" data-component="header"></label>
</p>
</form>


## show a email coupon.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/coupons/email" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/coupons/email"
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
<div id="execution-results-POSTapi-coupons-email" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-coupons-email"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-coupons-email"></code></pre>
</div>
<div id="execution-error-POSTapi-coupons-email" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-coupons-email"></code></pre>
</div>
<form id="form-POSTapi-coupons-email" data-method="POST" data-path="api/coupons/email" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-coupons-email', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-coupons-email" onclick="tryItOut('POSTapi-coupons-email');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-coupons-email" onclick="cancelTryOut('POSTapi-coupons-email');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-coupons-email" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/coupons/email</code></b>
</p>
<p>
<label id="auth-POSTapi-coupons-email" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-coupons-email" data-component="header"></label>
</p>
</form>


## show a public coupon.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/coupons/publicWithUser" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/coupons/publicWithUser"
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
<div id="execution-results-POSTapi-coupons-publicWithUser" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-coupons-publicWithUser"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-coupons-publicWithUser"></code></pre>
</div>
<div id="execution-error-POSTapi-coupons-publicWithUser" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-coupons-publicWithUser"></code></pre>
</div>
<form id="form-POSTapi-coupons-publicWithUser" data-method="POST" data-path="api/coupons/publicWithUser" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-coupons-publicWithUser', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-coupons-publicWithUser" onclick="tryItOut('POSTapi-coupons-publicWithUser');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-coupons-publicWithUser" onclick="cancelTryOut('POSTapi-coupons-publicWithUser');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-coupons-publicWithUser" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/coupons/publicWithUser</code></b>
</p>
<p>
<label id="auth-POSTapi-coupons-publicWithUser" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-coupons-publicWithUser" data-component="header"></label>
</p>
</form>


## find a coupon.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/coupons/referral" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/coupons/referral"
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
<div id="execution-results-POSTapi-coupons-referral" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-coupons-referral"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-coupons-referral"></code></pre>
</div>
<div id="execution-error-POSTapi-coupons-referral" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-coupons-referral"></code></pre>
</div>
<form id="form-POSTapi-coupons-referral" data-method="POST" data-path="api/coupons/referral" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-coupons-referral', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-coupons-referral" onclick="tryItOut('POSTapi-coupons-referral');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-coupons-referral" onclick="cancelTryOut('POSTapi-coupons-referral');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-coupons-referral" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/coupons/referral</code></b>
</p>
<p>
<label id="auth-POSTapi-coupons-referral" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-coupons-referral" data-component="header"></label>
</p>
</form>



