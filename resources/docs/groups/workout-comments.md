# Workout Comments   

APIs for managing  likes for post and comment on social part

## get a workout list with a customer&#039;s comment.

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/workout-comments/publish?customer_id=12&page_size=13&page_number=9" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/workout-comments/publish"
);

let params = {
    "customer_id": "12",
    "page_size": "13",
    "page_number": "9",
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
  'publish_date':'2021-05-23',
  'comment_count':2,
  'completed':true
}
]
}
```
<div id="execution-results-GETapi-workout-comments-publish" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-workout-comments-publish"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-workout-comments-publish"></code></pre>
</div>
<div id="execution-error-GETapi-workout-comments-publish" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-workout-comments-publish"></code></pre>
</div>
<form id="form-GETapi-workout-comments-publish" data-method="GET" data-path="api/workout-comments/publish" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-workout-comments-publish', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-workout-comments-publish" onclick="tryItOut('GETapi-workout-comments-publish');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-workout-comments-publish" onclick="cancelTryOut('GETapi-workout-comments-publish');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-workout-comments-publish" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/workout-comments/publish</code></b>
</p>
<p>
<label id="auth-GETapi-workout-comments-publish" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-workout-comments-publish" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>customer_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="customer_id" data-endpoint="GETapi-workout-comments-publish" data-component="query" required  hidden>
<br>
</p>
<p>
<b><code>page_size</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page_size" data-endpoint="GETapi-workout-comments-publish" data-component="query"  hidden>
<br>
if not, it is 20</p>
<p>
<b><code>page_number</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="page_number" data-endpoint="GETapi-workout-comments-publish" data-component="query"  hidden>
<br>
if not, it is 1, from 1</p>
</form>


## get a workout block list with a customer&#039;s comment for a specific day.

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/workout-comments/workout?publish_date=tenetur&customer_id=17" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/workout-comments/workout"
);

let params = {
    "publish_date": "tenetur",
    "customer_id": "17",
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
  'publish_date':'2021-05-23',
  'type':'basic', // or extra
  'block':{workout_block},
  'comment':{comment}
},
]
}
```
<div id="execution-results-GETapi-workout-comments-workout" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-workout-comments-workout"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-workout-comments-workout"></code></pre>
</div>
<div id="execution-error-GETapi-workout-comments-workout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-workout-comments-workout"></code></pre>
</div>
<form id="form-GETapi-workout-comments-workout" data-method="GET" data-path="api/workout-comments/workout" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-workout-comments-workout', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-workout-comments-workout" onclick="tryItOut('GETapi-workout-comments-workout');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-workout-comments-workout" onclick="cancelTryOut('GETapi-workout-comments-workout');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-workout-comments-workout" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/workout-comments/workout</code></b>
</p>
<p>
<label id="auth-GETapi-workout-comments-workout" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-workout-comments-workout" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
<p>
<b><code>publish_date</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="publish_date" data-endpoint="GETapi-workout-comments-workout" data-component="query" required  hidden>
<br>
</p>
<p>
<b><code>customer_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="customer_id" data-endpoint="GETapi-workout-comments-workout" data-component="query"  hidden>
<br>
</p>
</form>


## create a workout comment.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/workout-comments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"publish_date":"quidem","content":"molestiae","type":"hic","workout":"ea","dumbells_weight":2394.28678}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/workout-comments"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "publish_date": "quidem",
    "content": "molestiae",
    "type": "hic",
    "workout": "ea",
    "dumbells_weight": 2394.28678
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
<div id="execution-results-POSTapi-workout-comments" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-workout-comments"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-workout-comments"></code></pre>
</div>
<div id="execution-error-POSTapi-workout-comments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-workout-comments"></code></pre>
</div>
<form id="form-POSTapi-workout-comments" data-method="POST" data-path="api/workout-comments" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-workout-comments', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-workout-comments" onclick="tryItOut('POSTapi-workout-comments');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-workout-comments" onclick="cancelTryOut('POSTapi-workout-comments');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-workout-comments" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/workout-comments</code></b>
</p>
<p>
<label id="auth-POSTapi-workout-comments" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-workout-comments" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>publish_date</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="publish_date" data-endpoint="POSTapi-workout-comments" data-component="body" required  hidden>
<br>
date such as '2021-05-23'</p>
<p>
<b><code>content</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="content" data-endpoint="POSTapi-workout-comments" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="type" data-endpoint="POSTapi-workout-comments" data-component="body" required  hidden>
<br>
basic or extra</p>
<p>
<b><code>workout</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="workout" data-endpoint="POSTapi-workout-comments" data-component="body" required  hidden>
<br>
// the content of workout block JSON.stringify(workouts.current.blocks[step].content)</p>
<p>
<b><code>dumbells_weight</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="dumbells_weight" data-endpoint="POSTapi-workout-comments" data-component="body"  hidden>
<br>
</p>

</form>


## update a workout comment.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X PUT \
    "http://127.0.0.4/api/workout-comments/reiciendis" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"content":"illo","dumbells_weight":212}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/workout-comments/reiciendis"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "content": "illo",
    "dumbells_weight": 212
}

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json
{}
```
<div id="execution-results-PUTapi-workout-comments--workout_comment-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-workout-comments--workout_comment-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-workout-comments--workout_comment-"></code></pre>
</div>
<div id="execution-error-PUTapi-workout-comments--workout_comment-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-workout-comments--workout_comment-"></code></pre>
</div>
<form id="form-PUTapi-workout-comments--workout_comment-" data-method="PUT" data-path="api/workout-comments/{workout_comment}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-workout-comments--workout_comment-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-workout-comments--workout_comment-" onclick="tryItOut('PUTapi-workout-comments--workout_comment-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-workout-comments--workout_comment-" onclick="cancelTryOut('PUTapi-workout-comments--workout_comment-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-workout-comments--workout_comment-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/workout-comments/{workout_comment}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/workout-comments/{workout_comment}</code></b>
</p>
<p>
<label id="auth-PUTapi-workout-comments--workout_comment-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-workout-comments--workout_comment-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>workout_comment</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="workout_comment" data-endpoint="PUTapi-workout-comments--workout_comment-" data-component="url" required  hidden>
<br>
</p>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="PUTapi-workout-comments--workout_comment-" data-component="url" required  hidden>
<br>
comment id</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>content</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="content" data-endpoint="PUTapi-workout-comments--workout_comment-" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>dumbells_weight</code></b>&nbsp;&nbsp;<small>number</small>     <i>optional</i> &nbsp;
<input type="number" name="dumbells_weight" data-endpoint="PUTapi-workout-comments--workout_comment-" data-component="body"  hidden>
<br>
</p>

</form>



