# Comment on social part

APIs for managing  comment

## search comments.

<small class="badge badge-darkred">requires authentication</small>

This endpoint get comments on several scenarios.
Only comments with level1 = 0 can have replies.
<p>Replies  level1 > 0.</p>
<p>type = appendNext, it searchs only 4 comments with level1=0 from id</p>
<p>type = appendNextReplies, it searchs only 4 comments(replies) with level1>0 from id</p>
<p>type = append, it searchs only 4 previous comments with level1=0 from id</p>

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/comments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"id":9,"type":"voluptas"}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/comments"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "id": 9,
    "type": "voluptas"
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
 "comments":[
     {
         "id":1,
         "activity_id":13,
         "post_id":3,
         "customer_id":9,
         "parent_activity_id":4, parent item can be post or comment, so it means post or comment activity id
         "content":"content", it contains multi mentioned user such as @[Marlon Cañas](132) same as post content
         "level0":1,
         "level1":0,
         "likesCount":2,
         "like":false,
         "children":[], it exists when level1=0
         "nextChildrenCount":8,it exists when level1=0
     },
 ]
}
```
<div id="execution-results-GETapi-comments" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-comments"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-comments"></code></pre>
</div>
<div id="execution-error-GETapi-comments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-comments"></code></pre>
</div>
<form id="form-GETapi-comments" data-method="GET" data-path="api/comments" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-comments', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-comments" onclick="tryItOut('GETapi-comments');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-comments" onclick="cancelTryOut('GETapi-comments');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-comments" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/comments</code></b>
</p>
<p>
<label id="auth-GETapi-comments" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-comments" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="GETapi-comments" data-component="body" required  hidden>
<br>
comment from id or to id</p>
<p>
<b><code>type</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="type" data-endpoint="GETapi-comments" data-component="body" required  hidden>
<br>
appendNext or appendNextReplies or append</p>

</form>


## create a comment.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/comments" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"post_id":7,"content":"voluptas","parent_activity_id":"perspiciatis","condition":{"from_id":4}}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/comments"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "post_id": 7,
    "content": "voluptas",
    "parent_activity_id": "perspiciatis",
    "condition": {
        "from_id": 4
    }
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, creating comment):

```json

{
     "comment":{comment},// just created new comment
     "previousCommentsCount":5,
     "comments":[{comment}], comments from id to current new comment
     "nextCommentsCount":0,
     "commentsCount":14, total comment count, which contains replies
}
```
> Example response (200, creating comment reply):

```json

{
     "comment":{comment},// just created new comment
     "comments":[{comment}], all replies  to current new reply
     "nextChildrenCount":0,
     "commentsCount":14, total comment count, which contains replies
}
```
> Example response (403, failed{):

```json

"status":"failed"
}
```
<div id="execution-results-POSTapi-comments" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-comments"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-comments"></code></pre>
</div>
<div id="execution-error-POSTapi-comments" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-comments"></code></pre>
</div>
<form id="form-POSTapi-comments" data-method="POST" data-path="api/comments" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-comments', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-comments" onclick="tryItOut('POSTapi-comments');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-comments" onclick="cancelTryOut('POSTapi-comments');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-comments" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/comments</code></b>
</p>
<p>
<label id="auth-POSTapi-comments" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-comments" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>post_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="post_id" data-endpoint="POSTapi-comments" data-component="body" required  hidden>
<br>
post ID</p>
<p>
<b><code>content</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="content" data-endpoint="POSTapi-comments" data-component="body" required  hidden>
<br>
it contains multi mentioned user such as @[Marlon Cañas](132) same as post content</p>
<p>
<b><code>parent_activity_id</code></b>&nbsp;&nbsp;<small>optional</small>     <i>optional</i> &nbsp;
<input type="text" name="parent_activity_id" data-endpoint="POSTapi-comments" data-component="body"  hidden>
<br>
if exists, it is reply if no it is comment with level1=0</p>
<p>
<details>
<summary>
<b><code>condition</code></b>&nbsp;&nbsp;<small>object</small>     <i>optional</i> &nbsp;
<br>
optional when comment leve1 = 0</summary>
<br>
<p>
<b><code>condition.from_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="condition.from_id" data-endpoint="POSTapi-comments" data-component="body"  hidden>
<br>
it shows viewable comment first id</p>
</details>
</p>

</form>


## show a comment.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/comments/10" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/comments/10"
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
<div id="execution-results-GETapi-comments--comment-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-comments--comment-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-comments--comment-"></code></pre>
</div>
<div id="execution-error-GETapi-comments--comment-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-comments--comment-"></code></pre>
</div>
<form id="form-GETapi-comments--comment-" data-method="GET" data-path="api/comments/{comment}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-comments--comment-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-comments--comment-" onclick="tryItOut('GETapi-comments--comment-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-comments--comment-" onclick="cancelTryOut('GETapi-comments--comment-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-comments--comment-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/comments/{comment}</code></b>
</p>
<p>
<label id="auth-GETapi-comments--comment-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-comments--comment-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>comment</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="comment" data-endpoint="GETapi-comments--comment-" data-component="url" required  hidden>
<br>
comment ID</p>
</form>


## update a comment.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X PUT \
    "http://127.0.0.4/api/comments/8" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"content":"ut"}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/comments/8"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "content": "ut"
}

fetch(url, {
    method: "PUT",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
 "status":"ok",
 "comment":{comment}
}
```
<div id="execution-results-PUTapi-comments--comment-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-comments--comment-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-comments--comment-"></code></pre>
</div>
<div id="execution-error-PUTapi-comments--comment-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-comments--comment-"></code></pre>
</div>
<form id="form-PUTapi-comments--comment-" data-method="PUT" data-path="api/comments/{comment}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-comments--comment-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-comments--comment-" onclick="tryItOut('PUTapi-comments--comment-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-comments--comment-" onclick="cancelTryOut('PUTapi-comments--comment-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-comments--comment-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/comments/{comment}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/comments/{comment}</code></b>
</p>
<p>
<label id="auth-PUTapi-comments--comment-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-comments--comment-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>comment</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="comment" data-endpoint="PUTapi-comments--comment-" data-component="url" required  hidden>
<br>
comment ID</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>content</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="content" data-endpoint="PUTapi-comments--comment-" data-component="body" required  hidden>
<br>
it contains multi mentioned user such as @[Marlon Cañas](132) same as post content</p>

</form>


## delete a comment.

<small class="badge badge-darkred">requires authentication</small>

This endpoint deletes the comment and child replies and return comment struction.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/comments/1" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"from_id":7,"to_id":14}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/comments/1"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "from_id": 7,
    "to_id": 14
}

fetch(url, {
    method: "DELETE",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, creating comment):

```json

{
     "previousCommentsCount":5,
     "comments":[{comment}], comments from id to current new comment
     "nextCommentsCount":0,
     "commentsCount":14, total comment count, which contains replies
}
```
> Example response (200, creating comment reply):

```json

{
     "comments":[{comment}], all replies  to current new reply
     "nextChildrenCount":0,
     "commentsCount":14, total comment count, which contains replies
}
```
> Example response (403, failed):

```json
{
    "status": "0"
}
```
<div id="execution-results-DELETEapi-comments--comment-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-comments--comment-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-comments--comment-"></code></pre>
</div>
<div id="execution-error-DELETEapi-comments--comment-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-comments--comment-"></code></pre>
</div>
<form id="form-DELETEapi-comments--comment-" data-method="DELETE" data-path="api/comments/{comment}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-comments--comment-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-comments--comment-" onclick="tryItOut('DELETEapi-comments--comment-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-comments--comment-" onclick="cancelTryOut('DELETEapi-comments--comment-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-comments--comment-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/comments/{comment}</code></b>
</p>
<p>
<label id="auth-DELETEapi-comments--comment-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-comments--comment-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>comment</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="comment" data-endpoint="DELETEapi-comments--comment-" data-component="url" required  hidden>
<br>
comment ID</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>from_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="from_id" data-endpoint="DELETEapi-comments--comment-" data-component="body"  hidden>
<br>
it shows viewable comment first id when level1=0 required</p>
<p>
<b><code>to_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="to_id" data-endpoint="DELETEapi-comments--comment-" data-component="body"  hidden>
<br>
it shows viewable comment last id when level1=0 required</p>

</form>



