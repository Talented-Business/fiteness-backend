@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
<h2 style="color: #747474;font-weight:900;">¡Tu membresía está lista!</h2>
<p>{{$customer->first_name}}, para activar tu membresía debes transferir:</p>
<div style="border-radius: 5px;
    background-color: #156376;
    color: #fff;
    font-size: 30px;
    padding: 5px 15px;
    margin-right: 30px;
    display:inline-block;
    font-weight: 600;">
    ${{number_format((float)($amount+$bankFee),2,'.','')}}
</div>
<h3 style="color: #747474;font-weight:900;">Transferencia Bancaria</h3>
<p style="margin-bottom:0">Banco General</p>
<p style="margin-bottom:0;margin-top:0;">Fitemos Corp.</p>
<p style="margin-bottom:0;margin-top:0;">Cuenta Corriente</p>
<p style="margin-bottom:0;margin-top:0;">03-17-01-131135-6</p>
<p style="margin-bottom:0;margin-top:0;">hola@fitemos.com</p>
<table  border="0">
    <tbody>
        <tr style="">
            <td class="td" scope="row" style="with:50%">
                <h3 style="color: #747474;margin-top: 29px;font-weight:900;">Yappy</h3>
                <ul>
                    <li>Ingresar a <b>Yappy</b></li>
                    <li>Hacer click en <b>Enviar</b></li>
                    <li>Ir al <b>Directorio</b></li>
                    <li>Buscar <b>fitemoslatam</b></li>
                    <li>O bien, <b>escanear</b>:</li>
                </ul>
            </td>
            <td class="td"  style="with:50%">
                <img src="{{asset('media/company-logos/yappy.png')}}" alt="yappy">
            </td>
        </tr>
    </tbody>
</table>    
<h3 style="color: #747474;font-weight:900;">Notas</h3>
<p>
    La activación es un proceso manual y puede tardar unas horas. 
    Se te notificará inmediatamente al correo electrónico una vez la membresía esté activa. 
</p>
<p>
    Al suscribirse estará aceptando nuestros <a href="{{$url}}/terms_and_condition">términos y condiciones</a> y <a href="{{$url}}/privacy">políticas de privacidad</a>.
</p>
</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection