@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
    <tr><td style="color: #747474">
    <p>Hola {{$data['name']}},</p>

    <p>Hemos recibido una solicitud para reiniciar tu contraseña.</p>

    <p>Usa la siguiente contraseña temporal para acceder a tu cuenta de Fitemos.</p>

    <p>Contraseña:  <strong>{{$data['token']}}</strong></p>

    <p>Luego de iniciar sesión puedes cambiar tu contraseña en cualquier momento en la sección de perfil.</p>

    <p>Si necesitas asistencia puedes contactarnos.</p>

    <p>Hasta pronto,</p>

    <p>Team Fitemos</p>
</td></tr>
@endsection    

@section('footer')
    @parent
@endsection
