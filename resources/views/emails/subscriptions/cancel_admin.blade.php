<h1>you receive cancellation of a customer </h1>

<p>Customer ID = {{$customer->id}}</p>
<p>First Name = {{$customer->first_name}}</p>
<p>Last Name = {{$customer->last_name}}</p>
<p>Email = {{$customer->email}}</p>
<p>Cancelling Date = {{$cancelDate}}</p>
<p>Cancelling Now = {{$enableEnd}}</p>
<p>Stars = {{$qualityLevel}}</p>
<p>Motivo:{{$radioReason}} </p>
@if ($reasonText)
    <p>Motivo Text:{{$reasonText}} </p>
@endif
<p>Feedback</p>
<p  style="white-space: pre-line;">
    {{$recommendation}}
</p>