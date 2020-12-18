@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
<p>¡Hola!</p>
<p>Bienvenido a Fitemos.</p>
<p>Se te ha creado un acceso a Fitemos mediante este enlace: <a href="{{$url}}">{{$url}}</a></p>
@if ( $expiration)
    <p>Esta membresía tiene una vigencia hasta el {{$expiration}} </p>
@endif 
<p>Para ingresar, solo deberás completar el formulario y no necesitarás incluir ningún método de pago.</p>
<p>¡Nos vemos dentro!</p>
<br>
<p>Team Fitemos</p>
</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection