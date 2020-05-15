<?php

use Illuminate\Database\Seeder;
use App\Condition;
use App\Service;
use App\SubscriptionPlan;

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $service = Service::create(['title'=>'Fitness','description'=>'Fitness Workouts Subscription']);
        SubscriptionPlan::create(['service_id'=>$service->id,'type'=>"Free",'name'=>'Fitness Free membership','free_duration'=>7]);
        SubscriptionPlan::create(['service_id'=>$service->id,'type'=>"Paid",'name'=>'Fitness Paid membership','month_1'=>8.99,'month_3'=>23.97,'month_6'=>41.94,'month_12'=>null]);
        Condition::create(['title'=>'Nunca he hecho ejercicio',
            'service_id'=>$service->id,
            'summury'=>"Completar:
            20 {Burpees}
            En menos de 5 minutos.",
            'monday'=>'Workout',
            'tuesday'=>'Descanso Activo',
            'wednesday'=>'Workout',
            'thursday'=>'Descanso Blog',
            'friday'=>'Descanso Activo',
            'saturday'=>'Descanso Blog',
            'sunday'=>'Descanso Blog',
            ]);
        Condition::create(['title'=>'Hago ejercicio esporádicamente',
            'service_id'=>$service->id,
            'summury'=>"Completar:
            40 {Burpees}
            En menos de 5 minutos.",
            'monday'=>'Workout',
            'tuesday'=>'Descanso Activo',
            'wednesday'=>'Workout, Extra',
            'thursday'=>'Descanso Blog',
            'friday'=>'Workout',
            'saturday'=>'Descanso Blog',
            'sunday'=>'Descanso Blog',
            ]);
        Condition::create(['title'=>'Hago Ejercicio con frecuencia',
            'service_id'=>$service->id,
            'summury'=>"Completar:
            60 {Burpees}
            En menos de 5 minutos.",
            'monday'=>'Workout, Extra',
            'tuesday'=>'Workout',
            'wednesday'=>'Workout, Extra',
            'thursday'=>'Descanso Blog',
            'friday'=>'Workout',
            'saturday'=>'Descanso Blog',
            'sunday'=>'Descanso Blog',
            ]);
        Condition::create(['title'=>'Hago Ejercicio varios días a la semana',
            'service_id'=>$service->id,
            'summury'=>"Completar:
            80 {Burpees}
            En menos de 5 minutos.",
            'monday'=>'Workout, Extra',
            'tuesday'=>'Workout',
            'wednesday'=>'Workout, Extra',
            'thursday'=>'Descanso Blog',
            'friday'=>'Workout, Extra',
            'saturday'=>'Workout',
            'sunday'=>'Descanso Blog',
            ]);
        Condition::create(['title'=>'Estoy muy en forma y tengo experiencia',
            'service_id'=>$service->id,
            'summury'=>"",
            'monday'=>'Workout, Extra',
            'tuesday'=>'Workout, Extra',
            'wednesday'=>'Workout, Extra',
            'thursday'=>'Descanso Blog',
            'friday'=>'Workout, Extra',
            'saturday'=>'Workout, Extra',
            'sunday'=>'Descanso Blog',
            ]);
    }
}
