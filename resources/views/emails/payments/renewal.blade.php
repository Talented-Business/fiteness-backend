@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
<h2 style="color: #747474">Hola {{$firstName}}, tu Membresía {{$frequencyString}} se ha actualizado con éxito.</h2>
<br />
<table class="td" style="color: #747474; border: 1px solid #e5e5e5; border-collapse: collapse; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; width: 100%;" cellspacing="0" cellpadding="6" border="1">
    <tbody>
        <tr style="">
            <th class="td" scope="row" style="border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left; border-top-width: 4px;">
                Fecha
            </th>
            <td class="td" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left; border-top-width: 4px;">
                {{$doneDate}}
            </td>
        </tr>
        <tr>
            <th class="td" scope="row" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left;">
                Membresía
            </th>
            <td class="td" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left;">
                {{$frequencyString}}
            </td>
        </tr>
        <tr style="">
            <th class="td" scope="row" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left;">
                Valor
            </th>
            <td class="td" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left;">
                USD {{$amount}}
            </td>
        </tr>
        @if ( $coupon)
            <tr style="">
                <th class="td" scope="row" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left;">
                    Código: {{$coupon['code']}}
                </th>
                <td class="td" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left;">
                    -USD {{$coupon['amount']}}
                </td>
            </tr>
        @endif 
        <tr style="">
            <th class="td" scope="row" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left;">
                Total
            </th>
            <td class="td" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left;">
                USD {{$total}}
            </td>
        </tr>
        <tr style="">
            <th class="td" scope="row" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left;">
                Próxima Renovación
            </th>
            <td class="td" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left;">
                {{$nextPaymentDate}}
            </td>
        </tr>
        <tr style="">
            <th class="td" scope="row" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left;">
                Valor de Próxima Renovación
            </th>
            <td class="td" style="color: #747474; border: 1px solid #e5e5e5; padding: 12px; font-family: &quot;Helvetica Neue&quot;, Helvetica, Roboto, Arial, sans-serif; border-color: #e4e4e4; border-width: 1px; border-style: solid; padding-top: 12px; padding-bottom: 12px; padding-left: 12px; padding-right: 12px; text-align: left;">
                USD {{$nextPaymentTotal}}
            </td>
        </tr>
    </tbody>
</table>
 
<p>¡Gracias por Preferirnos!</p>
 
<p>Team Fitemos</p>

</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection