@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
<p>Hola {{$customer->first_name}} !</p>
<p>¡Sólo era para asegurarme que estés entrenando y pasándola genial!</p>
<p>Si todo está bien, no te preocupes, puedes omitir este correo.</p>
<p>De lo contrario, solo escríbeme a hola@fitemos.com, o bien toca el botón de abajo.</p>
<br>
<a href="mailto:hola@fitemos.com" style="
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
  " target="_blank">Contactar Fitemos</a>

<p>¡A la orden!</p>
<p>Marifer Urrutia</p>
<p>Team Fitemos</p>
</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection