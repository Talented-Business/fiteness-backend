@extends('layouts.email')


@section('header')
    @parent
@endsection

@section('content')
<tr><td style="color: #747474">
    <h2 style="color: #747474">{{$name}},¡Bienvenido a la familia Fitemos!</h2>
    <br/>
    <p>Acabas de dar un gran paso hacia tener una vida Fit.</p>

    <p>Fitemos es una plataforma conformada por un grupo de expertos en Fitness, Nutrición y Wellness. 
    De ahora en adelante, te enviaremos de forma diaria tus workouts personalizados, 
    días de descanso e información valiosa sobre entrenamientos, nutrición y tips varios que te ayudarán a lograr tus metas de una forma más rápida y efectiva.</p>

    <p>Algunos temas importantes:</p>
    <ol>
        <li>
            <span style="display:block;text-decoration:underline">Días de entrenamiento</span>
            <span>Actualmente, acorde a tu condición física actual, edad y objetivos, estarás entrenando</span>
            @if ($customer)
                @switch($customer->current_condition)
                    @case('1')
                    @case(1)
                        <span>
                            de Lunes a Viernes. Los Lunes y Miércoles serán tus días más intensos, los Martes y Viernes serán días activos a menor intensidad. 
                            Descansaremos y nos recuperaremos los Jueves, Sábados y Domingos.
                        </span>
                    @break
                    @case('2')
                    @case(2)
                        <span>
                            de Lunes a Viernes. El Miércoles será tu día más intenso de la semana, seguido de los Lunes y Viernes, el Martes tendrás entrenamientos de menor intensidad. 
                            Descansaremos y nos recuperaremos los Jueves, Sábados y Domingos.
                        </span>
                    @break
                    @case('3')
                    @case(3)
                        <span>
                            de Lunes a Viernes. Los Lunes y Miércoles serán tus días más intensos de la semana, seguido de los Martes y Viernes. 
                            Descansaremos y nos recuperaremos los Jueves, Sábados y Domingos.
                        </span>
                    @break
                    @case('4')
                    @case(4)
                        <span>
                            de Lunes a Sábado. Los Lunes, Miércoles, Viernes y Sábados serán tus días más intensos de la semana, seguido de los Martes. 
                            Descansaremos y nos recuperaremos los Jueves y Domingos.                        
                        </span>
                    @break
                    @case('5')
                    @case(5)
                        <span>
                            de Lunes a Sábado. Entrenarás con alta intensidad los Lunes, Martes, Miércoles, Viernes y Sábados. 
                            Descansaremos y nos recuperaremos los Jueves y Domingos.
                        </span>
                    @break
                @endswitch
            @endif
            <span>Es importante mencionar que a medida vayas avanzando con tu <i>Nivel de Condición Física (actualizar en el perfil)</i>, 
            tus días de entrenamiento e intensidad de las rutinas irán aumentando.</span>
        </li>
        <li>
            <span style="display:block;text-decoration:underline">Equipo</span>
            @if ($customer )
                @if ($customer->training_place=='Casa o Exterior')
                    <span>
                        Estarás recibiendo la programación para entrenamientos con peso corporal. 
                        Sin embargo, si tienes un par de mancuernas de peso medio, podrás actualizarlo en tu perfil. 
                        Y si no tienes, te recomendamos conseguir unas. Al tener un par de mancuernas las rutinas serán mucho más variadas y efectivas. 
                        <i>Esto siempre lo podrás actualizar en tu perfil (Entrenando con o sin pesas).</i>
                    </span>
                @else
                    <span>
                        Estarás recibiendo la programación para entrenamientos con un par de mancuernas. 
                        De no tener un par de mancuernas, podrás actualizarlo en tu perfil. En este caso te recomendamos conseguir unas de peso medio. 
                        Al tener un par de mancuernas las rutinas serán mucho más variadas y efectivas. 
                        <i>Esto siempre lo podrás actualizar en tu perfil (Entrenando con o sin pesas).</i>
                    </span>
                @endif    
            @endif
        </li>
        <li>
            <span style="display:block;text-decoration:underline">Objetivos</span>
            @if ($customer)
                @switch($objective)
                    @case('cardio')
                        <span>
                            Has seleccionado el Objetivo de Perder peso. 
                            Este Objetivo se enfocará en Extra Workouts de mediana a larga duración, 
                            con una intensidad de trabajo media que permita la quema de grasa. 
                        </span>
                        <br/>
                        <br/>
                        <span>
                            <i>
                                Los objetivos podrán ser editados desde tu perfil en cualquier momento.
                            </i>
                        </span>
                    @break
                    @case('fit')
                        <span>
                            Has seleccionado el Objetivo de Ponerte en forma. 
                            Este Objetivo se enfocará en Extra Workouts de alta intensidad. 
                            Normalmente veremos trabajo de intervalos, sprints o rutinas cortas e intensas, 
                            para desarrollar tu resistencia cardiovascular y muscular.
                        </span>
                        <br/>
                        <br/>
                        <span>
                            <i>
                                Los objetivos podrán ser editados desde tu perfil en cualquier momento.
                            </i>
                        </span>
                    @break
                    @case('strong')
                        <span>
                            Has seleccionado el Objetivo de Ganar musculatura. 
                            Este Objetivo se enfocará en Extra Workouts de tipo Musculación, 
                            con movimientos funcionales. Normalmente veremos altas repeticiones, con largos períodos de descanso, 
                            esto para permitir la hipertrofia y, por consiguiente, aumentar la masa muscular.
                        </span>
                        <br/>
                        <br/>
                        <span>
                            <i>
                                En este objetivo se recomienda contar con mancuernas de peso medio. Los objetivos podrán ser editados desde tu perfil en cualquier momento.
                            </i>
                        </span>
                    @break
                    @endswitch
            @endif
        </li>
        <li>
            <span style="display:block;text-decoration:underline">Benchmarks</span>
            <span>
                Los Benchmarks son pruebas predefinidas por el Team Fitemos. 
                Estas pruebas variarán en el tiempo y serán impuestas en tu programación. 
                Deberás realizarlas cada vez que sean solicitadas e ingresarlas en tu perfil. 
                De esta forma podrás monitorear tu progreso y resultados.
            </span>
        </li>
        <li>
            <span style="display:block;text-decoration:underline">Varios</span>
            <ul>
                <li>Los entrenamientos estarán llegando a las 7:00 pm del día anterior. 
                Es decir el entrenamiento de un “Lunes” te llegará el Domingo a las 7:00pm, con el fin de que puedas revisar la rutina con tiempo y prepararte para el día siguiente.</li>
                <li>Cualquier consulta que tengas relacionada a la plataforma, facturación o rutinas en sí, nos podrás contactar desde tu perfil.</li>
                <li>La membresía de Fitemos la podrás cancelar en cualquier momento, sin compromiso alguno desde tu perfil.</li>
            </ul>
        </li>
    </ol>

    <p>Felicidades nuevamente, ¡estás rodeado de personas altamente motivadas que desean acompañarte en todos tus logros!</p>

    <p>En el próximo correo te enviaremos el último workout programado, ¡en caso de que quieras iniciar de una vez!</p>

    <p>Muchos éxitos</p>

    <p>Atentamente,</p>

    <p style="font-weight:500">Team Fitemos</p>

</td></tr>    
@endsection    

@section('footer')
    @parent
@endsection