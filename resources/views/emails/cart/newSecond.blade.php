@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
<h2 style="color: #747474">Hola {{$firstName}}</h2>

<p>Hemos notado que aún no comienzas a entrenar con FITEMOS.</p>
<p>¿Sabías que no hay un mejor momento para potenciar tu vida FIT?</p>
<p>En estos tiempos en casa, con tiempo libre extra, sería genial que le dedicaras tiempo a lo más importante, tu salud.</p>
<p>Los hábitos se crean en 21 días y por eso te estamos ofreciendo {{$couponName}} con el código {{$couponCode}} al afiliarte hoy.</p>
<p>Sabemos que no podrás dejar nuestras rutinas personalizadas. Y si es el caso, solo tendrás que entrar a tu 
cuenta y desactivar la suscripción. No se realizará ningún cobro, siempre y cuando canceles antes del día 30.</p>
<p>¿Qué esperas?</p>
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
  " target="_blank">OBTENER 30 DÍAS GRATIS</a>

<p style="text-align:center">Cualquier consulta que tengas te podemos atender en nuestras redes sociales o bien, en hola@fitemos.com</p> 

<p>Marifer Urrutia<br/>
Team Fitemos</p>

</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection