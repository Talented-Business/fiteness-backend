<?php

namespace App;


trait WorkoutTrait
{
    private static function replace($content,$customerId)
    {
        $pattern = '/\{button +[".\'](.*)[".\']\}([\s\S]*)\{\/button\}/';
        $notes = preg_match($pattern, $content,$keywords);
        if($notes){
            $content = str_ireplace($keywords[0], "", $content);    
        }
        $content = str_ireplace("{h1}", "<h1>", $content);
        $content = str_ireplace("{/h1}", "</h1>", $content);
        $content = str_ireplace("{h2}", "<h2>", $content);
        $content = str_ireplace("{/h2}", "</h2>", $content);
        $shortcodes = Shortcode::where('status', '=', 'Active')->get();
        foreach ($shortcodes as $shortcode) {
            $url = env('APP_URL').'/api/customers/link?shortcode_id='.$shortcode->id;
            if($customerId)$url = $url.'&&customer_id='.$customerId;
            else $url = $shortcode->link;
            $content = str_replace("{{$shortcode->name}}", "<a href='$url' target='_blank'>$shortcode->name</a>", $content);
        }
        return [$content,$notes];
    }
    private static function replaceWhatsapp($content)
    {
        $pattern = '/\{button +[".\'](.*)[".\']\}([\s\S]*)\{\/button\}/';
        $notes = preg_match($pattern, $content,$keywords);
        if($notes){
            $content = str_ireplace($keywords[0], "", $content);    
        }
        $content = str_ireplace("{h1}", "*", $content);
        $content = str_ireplace("{/h1}", "*", $content);
        $content = str_ireplace("{h2}", "*_", $content);
        $content = str_ireplace("{/h2}", "_*", $content);
        $shortcodes = Shortcode::where('status', '=', 'Active')->get();
        $links = [];
        foreach ($shortcodes as $shortcode) {
            if (strpos($content, "{{$shortcode->name}}")) {
                $links[] = ['name' => $shortcode->name, 'link' => $shortcode->link];
            }

            $content = str_replace("{{$shortcode->name}}", $shortcode->name, $content);
        }
        if (count($links) > 0) {
            $content .= "\n\n";
            $content .= "*Ejercicios Explicados*\n";
            foreach ($links as $link) {
                $content .= $link['name'] . ' --> ' . $link['link'] . "\n";
            }
        }
        return [$content,$notes];
    }
    private static function replaceDashboard($content){
        $pattern = '/\{button +[".\'](.*)[".\']\}([\s\S]*)\{\/button\}/';
        $notes = preg_match($pattern, $content,$keywords);
        if($notes){
            $content = str_ireplace($keywords[0], '<spectioalButton/>', $content);
        }
        $lines = explode("\n",$content);
        $results = [];
        $shortcodes = Shortcode::where('status', '=', 'Active')->get();
        foreach($lines as $line){
            if($notes && strpos($line,'<spectioalButton/>')!==false){
                $line = str_ireplace("<spectioalButton/>", "&&", $line);
                $contents = explode('&&',$line);
                $results[] = ['tag'=>'modal','before_content'=>$contents[0],'after_content'=>$contents[1],'title'=>$keywords[1],'body'=>$keywords[2]];
            }else if(strpos($line,'{h1}') || strpos($line,'{/h1}')){
                $line = str_ireplace("{h1}", "", $line);
                $line = str_ireplace("{/h1}", "", $line);
                $results[] = ['tag'=>'h1','content'=>$line];
            }else if(strpos($line,'{h2}') || strpos($line,'{/h2}')){
                $line = str_ireplace("{h2}", "", $line);
                $line = str_ireplace("{/h2}", "", $line);
                $results[] = ['tag'=>'h2','content'=>$line];
            }else {
                $youtube=null;
                $afterContent="";
                foreach ($shortcodes as $shortcode) {
                    if(strpos($line,"{{$shortcode->name}}")!==false){
                        preg_match('/https:\/\/www.youtube.com\/watch\?v=(.*)/',$shortcode->link,$matches);
                        if(isset($matches[1])){
                            $line = str_replace("{{$shortcode->name}}", "&&", $line);
                            $youtube=['name'=>$shortcode->name,'vid'=>$matches[1]];
                        }
                        else{
                            preg_match('/https:\/\/youtu.be\/(.*)/',$shortcode->link,$matches);
                            if(isset($matches[1])){
                                $line = str_replace("{{$shortcode->name}}", "&&", $line);
                                $youtube=['name'=>$shortcode->name,'vid'=>$matches[1]];
                            }
                        }
                        $contents = explode('&&',$line);
                        $line = $contents[0];
                        $afterContent = $contents[1];
                    }
                }
                if($line !== ""||$youtube!=null)$results[] = ['tag'=>'p','before_content'=>$line,'after_content'=>$afterContent,'youtube'=>$youtube];
            }
        }
        return $results;
    }
    private static function findSendableContent($record,$publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId=null){
        $weekday = strtolower(date('l', strtotime($publishDate)));
        if (isset($workoutCondition[$weekday]) && $workoutCondition[$weekday]) {
            $content = $record->comentario."\n".$record->calentamiento;
            $sinContent = false;
            $blog = false;
            $workoutFilter = $workoutCondition[$weekday];
            if ($weightsCondition == 'sin pesas') {
                $sinContent = true;
            }
            if (strpos($workoutFilter, 'Workout')!==false) {
                if ($sinContent) {
                    $content = $content."\n".$record->sin_content;
                } else {
                    $content = $content."\n".$record->con_content;
                }
            }
            if ($objective == "strong") {
                if ($gender == "Male") {
                    $objectiveContent = $record->strong_male;
                } else {
                    $objectiveContent = $record->strong_female;
                }

            } else {
                if (isset($record[$objective])) {
                    $objectiveContent = $record[$objective];
                } else {
                    $objectiveContent = "";
                }
            }
            $weekday = strtolower(date('l', strtotime($publishDate)));
            if (strpos($workoutFilter, 'Extra')!==false ) {
                if($sinContent){
                    $content = $content . "\n" . $record->extra_sin;
                }else $content = $content . "\n" . $objectiveContent;
            }

            if (strpos($workoutFilter, 'Activo')!==false) {
                $content = $content . "\n" .$record->activo;
            }
            if (strpos($workoutFilter, 'Blog')!==false) {
                $content = $record->blog;
                $blog = true;
            }

            $spanishDate = ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A %d de %B del %Y", strtotime($publishDate))));
            if($customerId){
                $customer = Customer::find($customerId);
                if($customer){
                    $content = str_ireplace("{name}", $customer->first_name, $content);
                }
            }
            $whatsapp = $content;
            $dashboard = self::replaceDashboard($content);
            $content = self::replace($content,$customerId);
            $whatsapp = self::replaceWhatsapp($whatsapp);
            return ['date' => $spanishDate, 'content' => $content, 'whatsapp' => $whatsapp,'dashboard'=>$dashboard,'blog'=>$blog];
        }    
        return null;
    }
}