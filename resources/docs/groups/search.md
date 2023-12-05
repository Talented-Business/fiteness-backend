# Search   

APIs for searching people, eventos, posts

## search customers, shops, posts.

<small class="badge badge-darkred">requires authentication</small>

This endpoint searchs customers and shops and posts

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/search/all" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"search":"aut"}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/search/all"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "search": "aut"
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
 "people":[{customer}],
 "shop":[{company}],
 "posts":[{post}],
}
```
<div id="execution-results-GETapi-search-all" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-search-all"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-search-all"></code></pre>
</div>
<div id="execution-error-GETapi-search-all" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-search-all"></code></pre>
</div>
<form id="form-GETapi-search-all" data-method="GET" data-path="api/search/all" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-search-all', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-search-all" onclick="tryItOut('GETapi-search-all');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-search-all" onclick="cancelTryOut('GETapi-search-all');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-search-all" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/search/all</code></b>
</p>
<p>
<label id="auth-GETapi-search-all" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-search-all" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>search</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="search" data-endpoint="GETapi-search-all" data-component="body" required  hidden>
<br>
</p>

</form>


## search customers.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/search/customers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"search":"mollitia"}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/search/customers"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "search": "mollitia"
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
 "customers":[{customer}],
}
```
<div id="execution-results-GETapi-search-customers" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-search-customers"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-search-customers"></code></pre>
</div>
<div id="execution-error-GETapi-search-customers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-search-customers"></code></pre>
</div>
<form id="form-GETapi-search-customers" data-method="GET" data-path="api/search/customers" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-search-customers', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-search-customers" onclick="tryItOut('GETapi-search-customers');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-search-customers" onclick="cancelTryOut('GETapi-search-customers');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-search-customers" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/search/customers</code></b>
</p>
<p>
<label id="auth-GETapi-search-customers" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-search-customers" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>search</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="search" data-endpoint="GETapi-search-customers" data-component="body" required  hidden>
<br>
</p>

</form>


## search companies.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/search/companies" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"search":"labore"}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/search/companies"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "search": "labore"
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
 "companies":[{company}],
}
```
<div id="execution-results-GETapi-search-companies" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-search-companies"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-search-companies"></code></pre>
</div>
<div id="execution-error-GETapi-search-companies" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-search-companies"></code></pre>
</div>
<form id="form-GETapi-search-companies" data-method="GET" data-path="api/search/companies" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-search-companies', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-search-companies" onclick="tryItOut('GETapi-search-companies');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-search-companies" onclick="cancelTryOut('GETapi-search-companies');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-search-companies" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/search/companies</code></b>
</p>
<p>
<label id="auth-GETapi-search-companies" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-search-companies" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>search</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="search" data-endpoint="GETapi-search-companies" data-component="body" required  hidden>
<br>
</p>

</form>


## search posts.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/search/posts" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"search":"harum"}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/search/posts"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "search": "harum"
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
 "posts":[{post}],
}
```
<div id="execution-results-GETapi-search-posts" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-search-posts"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-search-posts"></code></pre>
</div>
<div id="execution-error-GETapi-search-posts" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-search-posts"></code></pre>
</div>
<form id="form-GETapi-search-posts" data-method="GET" data-path="api/search/posts" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-search-posts', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-search-posts" onclick="tryItOut('GETapi-search-posts');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-search-posts" onclick="cancelTryOut('GETapi-search-posts');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-search-posts" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/search/posts</code></b>
</p>
<p>
<label id="auth-GETapi-search-posts" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-search-posts" data-component="header"></label>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>search</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="search" data-endpoint="GETapi-search-posts" data-component="body" required  hidden>
<br>
</p>

</form>


## find customer or shop by username.


This endpoint
It find only shop unauthenticated, and it searchs customer or shop when authenticated.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/search/username" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"u":"repudiandae"}'

```

```javascript
const url = new URL(
    "http://127.0.0.4/api/search/username"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "u": "repudiandae"
}

fetch(url, {
    method: "GET",
    headers,
    body: JSON.stringify(body),
}).then(response => response.json());
```


> Example response (200, customer{):

```json

"id":5,
     "type":"customer",
     "username":"unique_user_name",
     "first_name":"First",
     "last_name":"Last",
     "postCount":10,
     "following":{follow},
     "followers":[{follow}],
     "followings":[{follow}],
     "relation":false,//it shows blocked or muted
     "medals":{
          'fromWorkout':100,// current level workout number
          'fromWorkoutImage':$src,//image
          'toWorkout':200,// next level workout number
          'toWorkoutImage':$toWorkoutImage,//image
          'workoutCount':75,// this total completed workout count
          'levelMedalImage':$src // level medal image
          'fromMonthWorkout':15,// current level month workout number
          'fromMonthWorkoutImage':$src,// current level month workout image
          'toMonthWorkout':25,// next level month workout number
          'toMonthWorkoutImage':$src,// next level month workout image
          'monthWorkoutCount':45, // this month total completed workout count
          'monthWorkoutTotal':46, // this month total workout count
          'monthShortName':"Feb",// spanish month short name
          'monthPercent':$monthPercent
     },
}
```
> Example response (200, company{):

```json

"id":5,
     "type":"company",
     "username":"unique_user_name",
     "name":"name",
     "description":"description",
     "phone":"phone",
     "mail":"email@gmail.com",
     "is_all_countries":"yes",// or "no"
     "mobile_phone":"1231231",
     "website_url":"https://www.fitemos.com",
     "address":"address",
     "facebook":"facebook",
     "instagram":"instagram",
     "twitter":"twitter",
     "horario":"horario",
     "logo":"src"
}
```
> Example response (403, Not found):

```json
{}
```
<div id="execution-results-GETapi-search-username" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-search-username"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-search-username"></code></pre>
</div>
<div id="execution-error-GETapi-search-username" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-search-username"></code></pre>
</div>
<form id="form-GETapi-search-username" data-method="GET" data-path="api/search/username" data-authed="0" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-search-username', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-search-username" onclick="tryItOut('GETapi-search-username');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-search-username" onclick="cancelTryOut('GETapi-search-username');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-search-username" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/search/username</code></b>
</p>
<h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
<p>
<b><code>u</code></b>&nbsp;&nbsp;<small>string</small>  &nbsp;
<input type="text" name="u" data-endpoint="GETapi-search-username" data-component="body" required  hidden>
<br>
</p>

</form>


## get notifications.

<small class="badge badge-darkred">requires authentication</small>

This endpoint shows latest 30 notifications.

> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/search/notifications" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/search/notifications"
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
 notifications:[{notification}]
}
```
<div id="execution-results-GETapi-search-notifications" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-search-notifications"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-search-notifications"></code></pre>
</div>
<div id="execution-error-GETapi-search-notifications" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-search-notifications"></code></pre>
</div>
<form id="form-GETapi-search-notifications" data-method="GET" data-path="api/search/notifications" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-search-notifications', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-search-notifications" onclick="tryItOut('GETapi-search-notifications');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-search-notifications" onclick="cancelTryOut('GETapi-search-notifications');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-search-notifications" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/search/notifications</code></b>
</p>
<p>
<label id="auth-GETapi-search-notifications" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-search-notifications" data-component="header"></label>
</p>
</form>


## add the list of all members with this order:  (active or inactive Will be included)
  Users with more interactions ( total workouts / latest 30 days   🡪 but now sure if Will exist Good performance calculating this each time the sectin Will be loaded)
  Users with pictures
  Users without pictures
  Inactive users

<small class="badge badge-darkred">requires authentication</small>



> Example request:

```bash
curl -X GET \
    -G "http://127.0.0.4/api/search/members" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/search/members"
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
 "customers":[{customer}],
}
```
<div id="execution-results-GETapi-search-members" hidden>
    <blockquote>Received response<span id="execution-response-status-GETapi-search-members"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-search-members"></code></pre>
</div>
<div id="execution-error-GETapi-search-members" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-search-members"></code></pre>
</div>
<form id="form-GETapi-search-members" data-method="GET" data-path="api/search/members" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('GETapi-search-members', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-GETapi-search-members" onclick="tryItOut('GETapi-search-members');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-GETapi-search-members" onclick="cancelTryOut('GETapi-search-members');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-GETapi-search-members" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-green">GET</small>
 <b><code>api/search/members</code></b>
</p>
<p>
<label id="auth-GETapi-search-members" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="GETapi-search-members" data-component="header"></label>
</p>
</form>



