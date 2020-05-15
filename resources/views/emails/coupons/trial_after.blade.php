@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
<h2 style="color: #747474">La decisión de estar Fit la tomas tú, nosotros nos encargamos del resto. </h2>
<p>{{$first_name}}, nos gustaría continuar acompañándote en tu vida Fit y que formes parte de la gran comunidad Fitemos.</p>
<p>Por eso decidimos obsequiarte un bono del 30% de descuento en ¡Cualquiera de nuestros planes!</p>
<p>Si te gustaría continuar recibiendo workouts como estos y continuar alcanzando tus metas junto a nosotros, solo haz click en este botón y goza de este descuento único:</p>
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
  " target="_blank">ENTRENAR CON FITEMOS</a>
<p>Este bono tendrá una validez de 48 horas.</p>
<p>Cualquier consulta que tengas escríbenos a hola@fitemos.com y te apoyaremos con mucho gusto.</p>
<p>¡Muchos éxitos y a seguir alcanzando metas!</p>
<br>
<p>Team Fitemos</p>
</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection