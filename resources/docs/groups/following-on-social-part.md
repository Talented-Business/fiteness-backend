# Following    on social part

APIs for managing  followings

## search followings or followers by 20 per page.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/follows/customer" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"customer_id":16,"type":"cumque","page_number":4}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/follows/customer"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 16,
    "type": "cumque",
    "page_number": 4
}

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
     "follows":[{customer}],
     "next":5,
}
```
<div id="execution-results-GETapi-follows-customer" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-follows-customer"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-follows-customer"></code></pre>
</div>
<div id="execution-error-GETapi-follows-customer" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-follows-customer"></code></pre>
</div>
<form id="form-GETapi-follows-customer" data-method="GET" data-path="api/follows/customer" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-follows-customer', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-follows-customer" onclick="tryItOut('GETapi-follows-customer');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-follows-customer" onclick="cancelTryOut('GETapi-follows-customer');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-follows-customer" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/follows/customer</code></b>
</p>
<p>
<label id="auth-GETapi-follows-customer" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-follows-customer" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>customer_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="customer_id" data-endpoint="GETapi-follows-customer" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="type" data-endpoint="GETapi-follows-customer" data-component="body" required  hidden>
<br>
followings or follower</p>
<p>
<b><code>page_number</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page_number" data-endpoint="GETapi-follows-customer" data-component="body"  hidden>
<br>
</p>

</form>



