@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
<h2 style="color: #747474">{{$first_name}}, tu plan de Fitness expira en tres días.</h2>
<p>Tu plan de fitness está por expirar. </p>
<p>Para continuar entrenando con Fitemos solo haz click en este botón, selecciona el plan de tu preferencia y completa el formulario de suscripción.</p>
  <a href="{{$url}}" style="
  width:430px;
  margin: 23px auto;
  display: block;
  padding: 12px;
  color: #e4e4e4;
  font-weight: 500;
  font-size: 23px;
  border-radius: 7px;
  background: -webkit-gradient(linear,left top,right top,from(#028fb4),to(#bfd734));
  background: linear-gradient(90deg,#028fb4,#bfd734);
  border: 0;
  font-style: normal;
  white-space: normal;
  align-items: center;
  justify-content: center;
  word-break: break-word;
  -webkit-align-items: center;
  -webkit-justify-content: center;
  text-decoration:none;
  " target="_blank">SIGUE ENTRENANDO CON FITEMOS</a>
<p>Cualquier consulta que tengas escríbenos a hola@fitemos.com y te apoyaremos con mucho gusto.</p>
<p>¡Gracias por preferirnos!</p>
<br>
<p>Team Fitemos</p>
</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection