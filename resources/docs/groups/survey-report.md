# Survey Report   

APIs for managing  survey report

## create survey report.

<small class="badge badge-darkred">requires authentication</small>

This endpoint.

> Example request:

```bash
curl -X POST \
    "http://127.0.0.4/api/survey-reports" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
    "http://127.0.0.4/api/survey-reports"
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
<div id="execution-results-POSTapi-survey-reports" hidden>
    <blockquote>Received response<span id="execution-response-status-POSTapi-survey-reports"></span>:</blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-survey-reports"></code></pre>
</div>
<div id="execution-error-POSTapi-survey-reports" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-survey-reports"></code></pre>
</div>
<form id="form-POSTapi-survey-reports" data-method="POST" data-path="api/survey-reports" data-authed="1" data-hasfiles="0" data-headers='{"Content-Type":"application\/json","Accept":"application\/json"}' onsubmit="event.preventDefault(); executeTryOut('POSTapi-survey-reports', this);">
<h3>
    Request&nbsp;&nbsp;&nbsp;
        <button type="button" style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-tryout-POSTapi-survey-reports" onclick="tryItOut('POSTapi-survey-reports');">Try it out ⚡</button>
    <button type="button" style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-canceltryout-POSTapi-survey-reports" onclick="cancelTryOut('POSTapi-survey-reports');" hidden>Cancel</button>&nbsp;&nbsp;
    <button type="submit" style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;" id="btn-executetryout-POSTapi-survey-reports" hidden>Send Request 💥</button>
    </h3>
<p>
<small class="badge badge-black">POST</small>
 <b><code>api/survey-reports</code></b>
</p>
<p>
<label id="auth-POSTapi-survey-reports" hidden>Authorization header: <b><code>Bearer </code></b><input type="text" name="Authorization" data-prefix="Bearer " data-endpoint="POSTapi-survey-reports" data-component="header"></label>
</p>
</form>



