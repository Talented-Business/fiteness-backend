@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
<h2 style="color: #747474">¡Esperamos te haya gustado la semana de prueba! </h2>
<p>{{$first_name}}, ha sido un placer entrenar esta primera semana contigo, mañana es tu último día y te entregaremos un workout del que no te podrás olvidar.</p>
<p>Esta fue una pequeña prueba de lo que es formar parte de la comunidad Fitemos. 
Recuerda que adicional a los workouts y guías nutricionales, tienes acceso a coaches que te guiarán en tu camino a la vida Fit. </p>
<p>De parte del Team Fitemos te queremos obsequiar un bono del 30% de descuento en ¡cualquiera de nuestros planes! Este bono tendrá una validez de 48 horas.</p>
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
  " target="_blank"> SIGUE ENTRENANDO CON FITEMOS </a>
<p>Cualquier consulta que tengas escríbenos a hola@fitemos.com y te apoyaremos con mucho gusto.</p>
<p>¡Eso es todo por hoy! Suerte en el workout de mañana.</p>
<br>
<p>Team Fitemos</p>
</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection