# Subscription   

APIs for managing  subscription

## cancel a subscription.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/subscriptions/cancel" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/subscriptions/cancel"
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
<div id="execution-results-POSTapi-subscriptions-cancel" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-subscriptions-cancel"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-subscriptions-cancel"></code></pre>
</div>
<div id="execution-error-POSTapi-subscriptions-cancel" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-subscriptions-cancel"></code></pre>
</div>
<form id="form-POSTapi-subscriptions-cancel" data-method="POST" data-path="api/subscriptions/cancel" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-subscriptions-cancel', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-subscriptions-cancel" onclick="tryItOut('POSTapi-subscriptions-cancel');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-subscriptions-cancel" onclick="cancelTryOut('POSTapi-subscriptions-cancel');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-subscriptions-cancel" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/subscriptions/cancel</code></b>
</p>
<p>
<label id="auth-POSTapi-subscriptions-cancel" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-subscriptions-cancel" data-component="header"></label>
</p>
</form>


## paypal ipn.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/subscriptions/paypal-ipn" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/subscriptions/paypal-ipn"
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
<div id="execution-results-POSTapi-subscriptions-paypal-ipn" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-subscriptions-paypal-ipn"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-subscriptions-paypal-ipn"></code></pre>
</div>
<div id="execution-error-POSTapi-subscriptions-paypal-ipn" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-subscriptions-paypal-ipn"></code></pre>
</div>
<form id="form-POSTapi-subscriptions-paypal-ipn" data-method="POST" data-path="api/subscriptions/paypal-ipn" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-subscriptions-paypal-ipn', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-subscriptions-paypal-ipn" onclick="tryItOut('POSTapi-subscriptions-paypal-ipn');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-subscriptions-paypal-ipn" onclick="cancelTryOut('POSTapi-subscriptions-paypal-ipn');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-subscriptions-paypal-ipn" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/subscriptions/paypal-ipn</code></b>
</p>
<p>
<label id="auth-POSTapi-subscriptions-paypal-ipn" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-subscriptions-paypal-ipn" data-component="header"></label>
</p>
</form>


## purchase a subscription by nmi.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/subscriptions/nmi" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/subscriptions/nmi"
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
<div id="execution-results-POSTapi-subscriptions-nmi" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-subscriptions-nmi"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-subscriptions-nmi"></code></pre>
</div>
<div id="execution-error-POSTapi-subscriptions-nmi" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-subscriptions-nmi"></code></pre>
</div>
<form id="form-POSTapi-subscriptions-nmi" data-method="POST" data-path="api/subscriptions/nmi" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-subscriptions-nmi', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-subscriptions-nmi" onclick="tryItOut('POSTapi-subscriptions-nmi');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-subscriptions-nmi" onclick="cancelTryOut('POSTapi-subscriptions-nmi');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-subscriptions-nmi" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/subscriptions/nmi</code></b>
</p>
<p>
<label id="auth-POSTapi-subscriptions-nmi" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-subscriptions-nmi" data-component="header"></label>
</p>
</form>


## checkout.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/subscriptions/checkout" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/subscriptions/checkout"
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
<div id="execution-results-POSTapi-subscriptions-checkout" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-subscriptions-checkout"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-subscriptions-checkout"></code></pre>
</div>
<div id="execution-error-POSTapi-subscriptions-checkout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-subscriptions-checkout"></code></pre>
</div>
<form id="form-POSTapi-subscriptions-checkout" data-method="POST" data-path="api/subscriptions/checkout" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-subscriptions-checkout', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-subscriptions-checkout" onclick="tryItOut('POSTapi-subscriptions-checkout');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-subscriptions-checkout" onclick="cancelTryOut('POSTapi-subscriptions-checkout');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-subscriptions-checkout" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/subscriptions/checkout</code></b>
</p>
<p>
<label id="auth-POSTapi-subscriptions-checkout" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-subscriptions-checkout" data-component="header"></label>
</p>
</form>


## renewal a subscription.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/subscriptions/iure/renewal" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/subscriptions/iure/renewal"
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
<div id="execution-results-POSTapi-subscriptions--id--renewal" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-subscriptions--id--renewal"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-subscriptions--id--renewal"></code></pre>
</div>
<div id="execution-error-POSTapi-subscriptions--id--renewal" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-subscriptions--id--renewal"></code></pre>
</div>
<form id="form-POSTapi-subscriptions--id--renewal" data-method="POST" data-path="api/subscriptions/{id}/renewal" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-subscriptions--id--renewal', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-subscriptions--id--renewal" onclick="tryItOut('POSTapi-subscriptions--id--renewal');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-subscriptions--id--renewal" onclick="cancelTryOut('POSTapi-subscriptions--id--renewal');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-subscriptions--id--renewal" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/subscriptions/{id}/renewal</code></b>
</p>
<p>
<label id="auth-POSTapi-subscriptions--id--renewal" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-subscriptions--id--renewal" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="id" data-endpoint="POSTapi-subscriptions--id--renewal" data-component="url" required  hidden>
<br>
</p>
</form>


## find a paypal plan.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/subscriptions/findPaypalPlan" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/subscriptions/findPaypalPlan"
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
<div id="execution-results-POSTapi-subscriptions-findPaypalPlan" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-subscriptions-findPaypalPlan"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-subscriptions-findPaypalPlan"></code></pre>
</div>
<div id="execution-error-POSTapi-subscriptions-findPaypalPlan" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-subscriptions-findPaypalPlan"></code></pre>
</div>
<form id="form-POSTapi-subscriptions-findPaypalPlan" data-method="POST" data-path="api/subscriptions/findPaypalPlan" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-subscriptions-findPaypalPlan', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-subscriptions-findPaypalPlan" onclick="tryItOut('POSTapi-subscriptions-findPaypalPlan');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-subscriptions-findPaypalPlan" onclick="cancelTryOut('POSTapi-subscriptions-findPaypalPlan');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-subscriptions-findPaypalPlan" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/subscriptions/findPaypalPlan</code></b>
</p>
<p>
<label id="auth-POSTapi-subscriptions-findPaypalPlan" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-subscriptions-findPaypalPlan" data-component="header"></label>
</p>
</form>


## create a subscription.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/subscriptions" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/subscriptions"
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
<div id="execution-results-POSTapi-subscriptions" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-subscriptions"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-subscriptions"></code></pre>
</div>
<div id="execution-error-POSTapi-subscriptions" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-subscriptions"></code></pre>
</div>
<form id="form-POSTapi-subscriptions" data-method="POST" data-path="api/subscriptions" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-subscriptions', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-subscriptions" onclick="tryItOut('POSTapi-subscriptions');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-subscriptions" onclick="cancelTryOut('POSTapi-subscriptions');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-subscriptions" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/subscriptions</code></b>
</p>
<p>
<label id="auth-POSTapi-subscriptions" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-subscriptions" data-component="header"></label>
</p>
</form>


## show a subscription.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/subscriptions/et" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/subscriptions/et"
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
<div id="execution-results-GETapi-subscriptions--subscription-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-subscriptions--subscription-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-subscriptions--subscription-"></code></pre>
</div>
<div id="execution-error-GETapi-subscriptions--subscription-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-subscriptions--subscription-"></code></pre>
</div>
<form id="form-GETapi-subscriptions--subscription-" data-method="GET" data-path="api/subscriptions/{subscription}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-subscriptions--subscription-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-subscriptions--subscription-" onclick="tryItOut('GETapi-subscriptions--subscription-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-subscriptions--subscription-" onclick="cancelTryOut('GETapi-subscriptions--subscription-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-subscriptions--subscription-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/subscriptions/{subscription}</code></b>
</p>
<p>
<label id="auth-GETapi-subscriptions--subscription-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-subscriptions--subscription-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>subscription</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="subscription" data-endpoint="GETapi-subscriptions--subscription-" data-component="url" required  hidden>
<br>
</p>
</form>


## create a free subscription.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/subscriptions/free" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/subscriptions/free"
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
<div id="execution-results-POSTapi-subscriptions-free" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-subscriptions-free"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-subscriptions-free"></code></pre>
</div>
<div id="execution-error-POSTapi-subscriptions-free" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-subscriptions-free"></code></pre>
</div>
<form id="form-POSTapi-subscriptions-free" data-method="POST" data-path="api/subscriptions/free" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-subscriptions-free', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-subscriptions-free" onclick="tryItOut('POSTapi-subscriptions-free');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-subscriptions-free" onclick="cancelTryOut('POSTapi-subscriptions-free');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-subscriptions-free" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/subscriptions/free</code></b>
</p>
<p>
<label id="auth-POSTapi-subscriptions-free" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-subscriptions-free" data-component="header"></label>
</p>
</form>



