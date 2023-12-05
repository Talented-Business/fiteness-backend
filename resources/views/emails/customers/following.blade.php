@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
<h3>{{$receiver_first_name}},</h3>
<p>{{$sender_first_name}} y otras personas te empezaron a seguir en Fitemos.</p>
<p>Para conectarte con ellos y el resto de los miembros de Fitemos solo debes activar tu cuenta.</p>
<p>Puedes hacerlo haciendo <a href="https://www.fitemos.com">click aquí</a> o ingresando a a tu cuenta en www.fitemos.com</p>
<p>¡Te vemos dentro!</p>
<br>
<p>Team Fitemos</p>
</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection