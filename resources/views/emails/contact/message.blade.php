@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
{!! $content !!}
</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection