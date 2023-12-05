# Post    on social part

APIs for managing post

## get posts by ids.

<small class="badge badge-darkred">requires authentication</small>

This endpoint gets current posts with ids

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/posts/sub-newsfeed" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"ids":[14,1]}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/posts/sub-newsfeed"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "ids": [
        14,
        1
    ]
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
  "items":[
     {
         post
     },
     {
         post
     },
     {
         post
     },
]
}
```
<div id="execution-results-POSTapi-posts-sub-newsfeed" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-posts-sub-newsfeed"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-posts-sub-newsfeed"></code></pre>
</div>
<div id="execution-error-POSTapi-posts-sub-newsfeed" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-posts-sub-newsfeed"></code></pre>
</div>
<form id="form-POSTapi-posts-sub-newsfeed" data-method="POST" data-path="api/posts/sub-newsfeed" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-posts-sub-newsfeed', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-posts-sub-newsfeed" onclick="tryItOut('POSTapi-posts-sub-newsfeed');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-posts-sub-newsfeed" onclick="cancelTryOut('POSTapi-posts-sub-newsfeed');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-posts-sub-newsfeed" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/posts/sub-newsfeed</code></b>
</p>
<p>
<label id="auth-POSTapi-posts-sub-newsfeed" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-posts-sub-newsfeed" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>ids</code></b>&nbsp;&nbsp;<small>integer[]</small>     <i>optional</i> &nbsp;
<input type="number" name="ids.0" data-endpoint="POSTapi-posts-sub-newsfeed" data-component="body"  hidden>
<input type="number" name="ids.1" data-endpoint="POSTapi-posts-sub-newsfeed" data-component="body" hidden>
<br>
</p>

</form>


## get random medias for a customer.

<small class="badge badge-darkred">requires authentication</small>

This endpoint get random medias of a customer who is public,  whom authenticated customer followed (not blocked, muted)

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/posts/random-medias/5" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/posts/random-medias/5"
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
     "self":[    // customer's 6 medias random order
         {media},
         {media}
     ],
     "other":[ // other customers' 12 medias  random order
         {media},
         {media}
     ]
}
```
<div id="execution-results-GETapi-posts-random-medias--customerId-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-posts-random-medias--customerId-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-posts-random-medias--customerId-"></code></pre>
</div>
<div id="execution-error-GETapi-posts-random-medias--customerId-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-posts-random-medias--customerId-"></code></pre>
</div>
<form id="form-GETapi-posts-random-medias--customerId-" data-method="GET" data-path="api/posts/random-medias/{customerId}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-posts-random-medias--customerId-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-posts-random-medias--customerId-" onclick="tryItOut('GETapi-posts-random-medias--customerId-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-posts-random-medias--customerId-" onclick="cancelTryOut('GETapi-posts-random-medias--customerId-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-posts-random-medias--customerId-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/posts/random-medias/{customerId}</code></b>
</p>
<p>
<label id="auth-GETapi-posts-random-medias--customerId-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-posts-random-medias--customerId-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>customerId</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="customerId" data-endpoint="GETapi-posts-random-medias--customerId-" data-component="url" required  hidden>
<br>
</p>
</form>


## get medias for a customer.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/posts/medias" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"customer_id":20,"media_id":6}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/posts/medias"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 20,
    "media_id": 6
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
     "medias":[    // customer's 20 medias from media_id order by id desc
         {media},
         {media}
     ],
}
```
<div id="execution-results-GETapi-posts-medias" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-posts-medias"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-posts-medias"></code></pre>
</div>
<div id="execution-error-GETapi-posts-medias" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-posts-medias"></code></pre>
</div>
<form id="form-GETapi-posts-medias" data-method="GET" data-path="api/posts/medias" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-posts-medias', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-posts-medias" onclick="tryItOut('GETapi-posts-medias');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-posts-medias" onclick="cancelTryOut('GETapi-posts-medias');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-posts-medias" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/posts/medias</code></b>
</p>
<p>
<label id="auth-GETapi-posts-medias" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-posts-medias" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>customer_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="customer_id" data-endpoint="GETapi-posts-medias" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>media_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="media_id" data-endpoint="GETapi-posts-medias" data-component="body"  hidden>
<br>
// from media_id</p>

</form>


## get Posts with ids and comment condition.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/posts/sync" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"ids":[{"id":1,"from_id":16,"to_id":5},{"id":1,"from_id":16,"to_id":5}]}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/posts/sync"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "ids": [
        {
            "id": 1,
            "from_id": 16,
            "to_id": 5
        },
        {
            "id": 1,
            "from_id": 16,
            "to_id": 5
        }
    ]
}

fetch(url, {
    method: "POST",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200):

```json

{
 "posts":[
  {
   "id":3,
   "activity_id":8,
   "content":"content",
   "tagFollowers":[
     {
       customer,
     },
     {
       customer,
     },
     ],
   "medias":[],
   "comments":[{comment}, {comment}], comments of from_id and to_id
   "previousCommentsCount":5,
   "nextCommentsCount":1,  //other customer recent comments count
   "commentsCount":8, // total comments count, which contains replies level.
   "likesCount":9,
   "like":true, // This means authenticated customer likes it
   "type":"general",
   "customer":{customer},// it contains post creator's info
  },
  {
   "id":4,
   "activity_id":9,
   "content":"content",
   "tagFollowers":[
     {
       customer,
     },
     {
       customer,
     },
     ],
   "medias":[],
   "comments":[{comment}, {comment}], comments of from_id and to_id
   "previousCommentsCount":5,
   "nextCommentsCount":1,  //other customer recent comments count
   "commentsCount":8, // total comments count, which contains replies level.
   "likesCount":9,
   "like":false,
   "type":"workout",
   "workout_spanish_date":"jan 5 2022",
   "workout_spanish_short_date":"jan 5 2022",
   "customer":{customer},// it contains post creator's info
  },
  {
   "id":5,
   "activity_id":10,
   "content":"content",
   "tagFollowers":[
     {
       customer,
     },
     {
       customer,
     },
     ],
   "medias":[],
   "comments":[{comment}, {comment}], comments of from_id and to_id
   "previousCommentsCount":5,
   "nextCommentsCount":1,  //other customer recent comments count
   "commentsCount":8, // total comments count, which contains replies level.
   "likesCount":9,
   "like":false,
   "type":"workout",
   "workout_spanish_date":"jan 5 2022",
   "workout_spanish_short_date":"jan 5 2022",
   "customer":{customer},// it contains post creator's info
  },
]
}
```
<div id="execution-results-POSTapi-posts-sync" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-posts-sync"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-posts-sync"></code></pre>
</div>
<div id="execution-error-POSTapi-posts-sync" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-posts-sync"></code></pre>
</div>
<form id="form-POSTapi-posts-sync" data-method="POST" data-path="api/posts/sync" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-posts-sync', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-posts-sync" onclick="tryItOut('POSTapi-posts-sync');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-posts-sync" onclick="cancelTryOut('POSTapi-posts-sync');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-posts-sync" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/posts/sync</code></b>
</p>
<p>
<label id="auth-POSTapi-posts-sync" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-posts-sync" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<details>
<summary>
<b><code>ids</code></b>&nbsp;&nbsp;<small>object[]</small>  &nbsp;
<br>
</summary>
<br>
<p>
<b><code>ids[].id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="ids.0.id" data-endpoint="POSTapi-posts-sync" data-component="body" required  hidden>
<br>
post_id</p>
<p>
<b><code>ids[].from_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="ids.0.from_id" data-endpoint="POSTapi-posts-sync" data-component="body" required  hidden>
<br>
from comment_id</p>
<p>
<b><code>ids[].to_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="ids.0.to_id" data-endpoint="POSTapi-posts-sync" data-component="body" required  hidden>
<br>
to comment_id</p>
</details>
</p>

</form>


## read a post.

<small class="badge badge-darkred">requires authentication</small>

This endpoint save reading time on post

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/posts/11/read" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/posts/11/read"
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
<div id="execution-results-POSTapi-posts--id--read" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-posts--id--read"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-posts--id--read"></code></pre>
</div>
<div id="execution-error-POSTapi-posts--id--read" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-posts--id--read"></code></pre>
</div>
<form id="form-POSTapi-posts--id--read" data-method="POST" data-path="api/posts/{id}/read" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-posts--id--read', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-posts--id--read" onclick="tryItOut('POSTapi-posts--id--read');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-posts--id--read" onclick="cancelTryOut('POSTapi-posts--id--read');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-posts--id--read" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/posts/{id}/read</code></b>
</p>
<p>
<label id="auth-POSTapi-posts--id--read" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-posts--id--read" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="id" data-endpoint="POSTapi-posts--id--read" data-component="url" required  hidden>
<br>
</p>
</form>


## search posts for specific customer.

<small class="badge badge-darkred">requires authentication</small>

This endpoint returns 3 posts from post_id order by post id desc

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/posts" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"customer_id":20,"post_id":9}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/posts"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "customer_id": 20,
    "post_id": 9
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
 "status":"ok",
 "posts":[
  {
   "id":3,
   "activity_id":8,
   "content":"content",
   "tagFollowers":[
     {
       customer,
     },
     {
       customer,
     },
     ],
   "medias":[],
   "comments":[{comment}], //it contains last comment
   "previousCommentsCount":5, // total (comment level)comments(not include replies) count -1
   "commentsCount":8, // total comments count, which contains replies level.
   "likesCount":9,
   "like":true, // This means authenticated customer likes it
   "type":"general",
   "customer":{customer},// it contains post creator's info
  },
  {
   "id":4,
   "activity_id":9,
   "content":"content",
   "tagFollowers":[
     {
       customer,
     },
     {
       customer,
     },
     ],
   "medias":[],
   "comments":[{comment}], //it contains last comment
   "previousCommentsCount":5, // total (comment level)comments(not include replies) count -1
   "commentsCount":8, // total comments count, which contains replies level.
   "likesCount":9,
   "like":false,
   "type":"workout",
   "workout_spanish_date":"jan 5 2022",
   "workout_spanish_short_date":"jan 5 2022",
   "customer":{customer},// it contains post creator's info
  },
  {
   "id":5,
   "activity_id":10,
   "content":"content",
   "tagFollowers":[
     {
       customer,
     },
     {
       customer,
     },
     ],
   "medias":[],
   "comments":[{comment}], //it contains last comment
   "previousCommentsCount":5, // total (comment level)comments(not include replies) count -1
   "commentsCount":8, // total comments count, which contains replies level.
   "likesCount":9,
   "like":false,
   "type":"workout",
   "workout_spanish_date":"jan 5 2022",
   "workout_spanish_short_date":"jan 5 2022",
   "customer":{customer},// it contains post creator's info
  },
],
"customerProfile":true,// if true, it means the customer is public or when the customer is private, authenticated customer already followed the customer. if false, the customer is private, authenticated customer have no following relationship with the customer or blocked or muted
}
```
<div id="execution-results-GETapi-posts" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-posts"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-posts"></code></pre>
</div>
<div id="execution-error-GETapi-posts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-posts"></code></pre>
</div>
<form id="form-GETapi-posts" data-method="GET" data-path="api/posts" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-posts', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-posts" onclick="tryItOut('GETapi-posts');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-posts" onclick="cancelTryOut('GETapi-posts');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-posts" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/posts</code></b>
</p>
<p>
<label id="auth-GETapi-posts" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-posts" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>customer_id</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="customer_id" data-endpoint="GETapi-posts" data-component="body" required  hidden>
<br>
</p>
<p>
<b><code>post_id</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="post_id" data-endpoint="GETapi-posts" data-component="body"  hidden>
<br>
last post id</p>

</form>


## create a post.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/posts" \
    -H "Content-Type: multipart/form-data" \
    -H "Accept: application/json" \
    -F "location=eaque" \
    -F "content=facere" \
    -F "tag_followers[]=2" \
    -F "medias[]=@/tmp/phphwPvk3" 
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/posts"
);

let headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('location', 'eaque');
body.append('content', 'facere');
body.append('tag_followers[]', '2');
body.append('medias[]', document.querySelector('input[name="medias[]"]').files[0]);

fetch(url, {
    method: "POST",
    headers,
    body,
}).then(response => response.json());
```


> Example response (200):

```json

{
 "status:"ok",
 "post":{
 "id":3,
 "activity_id":5,
 "content":"content",
 "tag_followers":[4,5,8],
}
}
```
<div id="execution-results-POSTapi-posts" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-posts"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-posts"></code></pre>
</div>
<div id="execution-error-POSTapi-posts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-posts"></code></pre>
</div>
<form id="form-POSTapi-posts" data-method="POST" data-path="api/posts" data-authed="1" data-hasfiles="1" data-headers='{"Content-Type":"multipart\/form-data","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-posts', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-posts" onclick="tryItOut('POSTapi-posts');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-posts" onclick="cancelTryOut('POSTapi-posts');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-posts" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/posts</code></b>
</p>
<p>
<label id="auth-POSTapi-posts" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-posts" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>location</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="location" data-endpoint="POSTapi-posts" data-component="body"  hidden>
<br>
</p>
<p>
<b><code>content</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="content" data-endpoint="POSTapi-posts" data-component="body"  hidden>
<br>
contains multi mentioned user such as @[Marlon Cañas](132)</p>
<p>
<b><code>tag_followers</code></b>&nbsp;&nbsp;<small>integer[]</small>     <i>optional</i> &nbsp;
<input type="number" name="tag_followers.0" data-endpoint="POSTapi-posts" data-component="body"  hidden>
<input type="number" name="tag_followers.1" data-endpoint="POSTapi-posts" data-component="body" hidden>
<br>
</p>
<p>
<b><code>medias</code></b>&nbsp;&nbsp;<small>file[]</small>     <i>optional</i> &nbsp;
<input type="file" name="medias.0" data-endpoint="POSTapi-posts" data-component="body"  hidden>
<input type="file" name="medias.1" data-endpoint="POSTapi-posts" data-component="body" hidden>
<br>
video, image max size 200M</p>

</form>


## show a post.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/posts/14" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"comment":11}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/posts/14"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "comment": 11
}

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, comment not 1):

```json

{
   "id":5,
   "activity_id":10,
   "content":"content",
   "tagFollowers":[
     {
       customer,
     },
     {
       customer,
     },
     ],
   "medias":[],
   "comments":[{comment}], //it contains last comment
   "previousCommentsCount":5, // total (comment level)comments(not include replies) count -1
   "commentsCount":8, // total comments count, which contains replies level.
   "likesCount":9,
   "like":false,
   "type":"general",
   "customer":{customer},// it contains post creator's info
}
```
> Example response (200, comment 1):

```json

{
   "id":5,
   "activity_id":10,
   "content":"content",
   "tagFollowers":[
     {
       customer,
     },
     {
       customer,
     },
     ],
   "medias":[],
   "comments":[{comment}], //it contains all comments
   "previousCommentsCount":0,
   "commentsCount":8, // total comments count, which contains replies level.
   "likesCount":9,
   "like":false,
   "type":"general",
   "customer":{customer},// it contains post creator's info
}
```
<div id="execution-results-GETapi-posts--post-" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-posts--post-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-posts--post-"></code></pre>
</div>
<div id="execution-error-GETapi-posts--post-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-posts--post-"></code></pre>
</div>
<form id="form-GETapi-posts--post-" data-method="GET" data-path="api/posts/{post}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-posts--post-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-posts--post-" onclick="tryItOut('GETapi-posts--post-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-posts--post-" onclick="cancelTryOut('GETapi-posts--post-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-posts--post-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/posts/{post}</code></b>
</p>
<p>
<label id="auth-GETapi-posts--post-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-posts--post-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>post</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="post" data-endpoint="GETapi-posts--post-" data-component="url" required  hidden>
<br>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>comment</code></b>&nbsp;&nbsp;<small>integer</small>     <i>optional</i> &nbsp;
<input type="number" name="comment" data-endpoint="GETapi-posts--post-" data-component="body"  hidden>
<br>
when comment = 1, it contains all comments, but not include replies</p>

</form>


## update a post.

<small class="badge badge-darkred">requires authentication</small>

This endpoint update post data. but image or videos has not been update immediately, because fitemos saves medias asynchronously

> Example request:

```bash
curl -X PUT \
    "http://127.0.0.4/api/posts/8" \
    -H "Content-Type: multipart/form-data" \
    -H "Accept: application/json" \
    -F "location=omnis" \
    -F "content=dicta" \
    -F "tag_followers[]=17" \
    -F "media_ids[]=17" \
    -F "medias[]=@/tmp/phpXM0zUZ" 
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/posts/8"
);

let headers = {
    "Content-Type": "multipart/form-data",
    "Accept": "application/json",
};

const body = new FormData();
body.append('location', 'omnis');
body.append('content', 'dicta');
body.append('tag_followers[]', '17');
body.append('media_ids[]', '17');
body.append('medias[]', document.querySelector('input[name="medias[]"]').files[0]);

fetch(url, {
    method: "PUT",
    headers,
    body,
}).then(response => response.json());
```


> Example response (200):

```json

{
 "status:"ok",
 "post":{
 "id":3,
 "activity_id":5,
 "content":"content",
 "tag_followers":[4,5,8],
}
}
```
<div id="execution-results-PUTapi-posts--post-" hidden>
    <blockquote>Received response<span id="execution-response-status-PUTapi-posts--post-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-PUTapi-posts--post-"></code></pre>
</div>
<div id="execution-error-PUTapi-posts--post-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PUTapi-posts--post-"></code></pre>
</div>
<form id="form-PUTapi-posts--post-" data-method="PUT" data-path="api/posts/{post}" data-authed="1" data-hasfiles="1" data-headers='{"Content-Type":"multipart\/form-data","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('PUTapi-posts--post-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-PUTapi-posts--post-" onclick="tryItOut('PUTapi-posts--post-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-PUTapi-posts--post-" onclick="cancelTryOut('PUTapi-posts--post-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-PUTapi-posts--post-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-darkblue">PUT</small>
 <b><code>api/posts/{post}</code></b>
</p>
<p>
<small class="badge badge-purple">PATCH</small>
 <b><code>api/posts/{post}</code></b>
</p>
<p>
<label id="auth-PUTapi-posts--post-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="PUTapi-posts--post-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>post</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="post" data-endpoint="PUTapi-posts--post-" data-component="url" required  hidden>
<br>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>location</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="location" data-endpoint="PUTapi-posts--post-" data-component="body"  hidden>
<br>
</p>
<p>
<b><code>content</code></b>&nbsp;&nbsp;<small>string</small>     <i>optional</i> &nbsp;
<input type="text" name="content" data-endpoint="PUTapi-posts--post-" data-component="body"  hidden>
<br>
contains multi mentioned user such as @[Marlon Cañas](132)</p>
<p>
<b><code>tag_followers</code></b>&nbsp;&nbsp;<small>integer[]</small>     <i>optional</i> &nbsp;
<input type="number" name="tag_followers.0" data-endpoint="PUTapi-posts--post-" data-component="body"  hidden>
<input type="number" name="tag_followers.1" data-endpoint="PUTapi-posts--post-" data-component="body" hidden>
<br>
</p>
<p>
<b><code>media_ids</code></b>&nbsp;&nbsp;<small>integer[]</small>     <i>optional</i> &nbsp;
<input type="number" name="media_ids.0" data-endpoint="PUTapi-posts--post-" data-component="body"  hidden>
<input type="number" name="media_ids.1" data-endpoint="PUTapi-posts--post-" data-component="body" hidden>
<br>
original medias</p>
<p>
<b><code>medias</code></b>&nbsp;&nbsp;<small>file[]</small>     <i>optional</i> &nbsp;
<input type="file" name="medias.0" data-endpoint="PUTapi-posts--post-" data-component="body"  hidden>
<input type="file" name="medias.1" data-endpoint="PUTapi-posts--post-" data-component="body" hidden>
<br>
new meidas</p>

</form>


## delete a post.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X DELETE \
    "http://127.0.0.4/api/posts/15" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/posts/15"
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


> Example response (200, success):

```json
{
    "status": "1",
    "msg": "success"
}
```
> Example response (200, failed):

```json
{
    "status": "0",
    "msg": "fail"
}
```
<div id="execution-results-DELETEapi-posts--post-" hidden>
    <blockquote>Received response<span id="execution-response-status-DELETEapi-posts--post-"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-posts--post-"></code></pre>
</div>
<div id="execution-error-DELETEapi-posts--post-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-posts--post-"></code></pre>
</div>
<form id="form-DELETEapi-posts--post-" data-method="DELETE" data-path="api/posts/{post}" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('DELETEapi-posts--post-', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-DELETEapi-posts--post-" onclick="tryItOut('DELETEapi-posts--post-');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-DELETEapi-posts--post-" onclick="cancelTryOut('DELETEapi-posts--post-');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-DELETEapi-posts--post-" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-red">DELETE</small>
 <b><code>api/posts/{post}</code></b>
</p>
<p>
<label id="auth-DELETEapi-posts--post-" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="DELETEapi-posts--post-" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
<p>
<b><code>post</code></b>&nbsp;&nbsp;<small>integer</small>  &nbsp;
<input type="number" name="post" data-endpoint="DELETEapi-posts--post-" data-component="url" required  hidden>
<br>
the id of post</p>
</form>



