@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
<h2 style="color: #747474">Hola {{$firstName}}</h2>

<p>Hemos notado que no renovaste tu plan con FITEMOS.</p>
<p>Ya conociste lo efectivo y divertido que es FITEMOS.</p>
<p>Solo te quería recordar todos los usos que le puedes dar:</p>
<p>Para entrenar en casa, para entrengar en el GYM, para entrenar en el parque los fines de semana, 
para entrenar en la playa o cuando estés de viaje o incluso para entrenar con tus allegados.</p>
<p>Te extrañamos, es por eso que te queremos ofrecer <strong>{{$percent}} de descuento vitalicio en cualquiera de nuestros planes</strong>.</p>
<a href="{{$url}}" style="
  width:330px;
  margin: 23px auto;
  display: block;
  padding: 12px;
  color: #e4e4e4;
  font-weight: 500;
  font-size: 23px;
  border-radius: 7px;
  background: -webkit-gradient(linear,left top,right top,from(#028fb4),to(#bfd734));
  background: linear-gradient(90deg,#028fb4,#bfd734);
  background-color:#028fb4;
  border: 0;
  font-style: normal;
  white-space: normal;
  align-items: center;
  justify-content: center;
  word-break: break-word;
  -webkit-align-items: center;
  -webkit-justify-content: center;
  text-decoration:none;
  text-align:center;
  " target="_blank">OBTENER {{$percent}} VITALICIO</a>

<p style="text-align:center">Cualquier consulta que tengas te podemos atender en nuestras redes sociales o bien, en hola@fitemos.com</p> 

<p>Marifer Urrutia<br/>
Team Fitemos</p>
</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection