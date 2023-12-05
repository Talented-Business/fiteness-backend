@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
    <h2 style="color: #747474">¡Bienvenido {{$name}}! Descarga el APP aquí y únete al Chat.</h2>
    <br/>
    <p>{{$name}}, bienvenido a Fitemos. Te adjuntamos los links para que descargues el APP (también puedes buscarlo en el App Store o Google Play).</p>

    <p>Descargar iOs – <a href="https://apps.apple.com/us/app/fitemos/id1549350889"
                    style="
                    padding: 12px;
                    ">App Store</a></p>

    <p>Descargar Android – <a href="https://play.google.com/store/apps/details?id=com.dexterous.fitemos&hl=es_419&gl=US"
                    style="
                    padding: 12px;
                    ">Google Play</a></p>

    <p>Por otro lado, <a href="https://wa.me/message/FRSTYOD34VWVC1"
                    style="
                    padding: 12px;
                    ">haz click aquí y escríbenos al WhatsApp</a>
    así te podemos dar la bienvenida, 
    incluirte al grupo y quedas conectado con soporte. (WhatsApp: +507 832 7558).</p>
    
    <p style="font-weight:500">Team Fitemos</p>

</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection