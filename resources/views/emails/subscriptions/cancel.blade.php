@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
<h2 style="color: #747474">El plan de Fitness, ha sido cancelado exitosamente.</h2>
<p>{{$first_name}}, has cancelado exitosamente el plan de Fitness el día {{$cancel_date}}.</p>
<p>Recibirás la programación hasta el día {{$subscription_date}}, y ya no se volverán hacer renovaciones adicionales.</p>
<p>Estamos agradecidos de haber sido parte de la trayectoria en camino a la vida Fit. Esperamos que vuelvas pronto y puedas probar nuestros modulos de running, yoga y nutrición que están en desarrollo.</p>
<p>¡Hasta pronto {{$first_name}}!</p>
<p>Vuelve pronto,</p>
<br>
<p>Team Fitemos</p>
</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection