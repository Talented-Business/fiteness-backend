@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
    {!! $content !!}
    <br />
    <a href="{{$homeUrl}}" style="
  width:280px;
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
  " target="_blank">VER ENTRENAMIENTO</a>
    <br />
  <small style="text-align: center;"><b>Nota:</b> Para ver las Notas y Completar el Workout debes realizarlo desde el perfil.</small>
  <br />
  <br />
  <a href="{{$homeUrl}}/#unsubscribe"
    style="
    color: #747474;
    margin: 23px auto;
    display: block;
    padding: 12px;
    text-decoration:none;
    ">Darse de baja </a>
</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection