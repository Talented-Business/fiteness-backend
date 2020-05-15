@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
  <div style="color: #747474"><strong style="margin-left:100px;width:100px;display:inline-block;">Non Active</strong>: {{$nonActive}}</div>
  <div style="color: #747474"><strong style="margin-left:100px;width:100px;display:inline-block;">Active</strong>: {{$active}}</div>
  <div style="color: #747474"><strong style="margin-left:100px;width:100px;display:inline-block;">Total</strong>: ${{$total}}</div> 
</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection