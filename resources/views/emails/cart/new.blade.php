@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
<h2 style="color: #747474">¡Hola {{$firstName}}</h2>

<p>Mi nombre es Marifer, un placer.</p>
<p>He notado que aún estás decidiéndote iniciar con Fitemos.</p>
<p>En realidad, sería genial si pudieses probar lo efectivo que es el plan de entrenamiento y lo grandiosa que es nuestra comunidad.</p>
<p>Los hábitos se crean en 21 días y por eso te estamos ofreciendo 1 MES GRATIS al empezar a entrenar hoy.</p>
<p>Prueba Fitemos sin compromiso. Si por alguna razón, los entrenamientos, la comunidad y el Team Fitemos, no se ajustan a lo que estabas buscando.</p>
<p>Podrás cancelar la membresía sin compromiso alguno.</p>
<p>Por otro lado, estaré contenta de poder solventar cualquier duda que tengas, a través de:<br/>
<a href="mailto:hola@fitemos.com">hola@fitemos.com</a></p>
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

<p style="text-align:center">Un saludo y ¡feliz entrenamientos!</p> 

<p>Marifer Urrutia<br/>
Team Fitemos</p>

</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection