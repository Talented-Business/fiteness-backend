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
    private static function getNotes($record, $slug){
        $result = null;
        if(isset($record->{$slug})&&$record->{$slug}){
            $result = ['content'=>$record->{$slug},'note'=>$record->{$slug.'_note'}];
            if(isset($record->{$slug.'_timer_type'}) && $record->{$slug.'_timer_type'}){
                $result['timer_type'] = $record->{$slug.'_timer_type'};
                $result['timer_work'] = $record->{$slug.'_timer_work'};
                if($record->{$slug.'_timer_type'}=='tabata'){
                    $result['timer_round'] = $record->{$slug.'_timer_round'};
                    $result['timer_rest'] = $record->{$slug.'_timer_rest'};
                }
            }
        }
        return $result;
    }
    private static function findSendableContent($record,$publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId=null){
        $weekday = strtolower(date('l', strtotime($publishDate)));
        if (isset($workoutCondition[$weekday]) && $workoutCondition[$weekday]) {
            $block = ['content'=>$record->comentario];
            if($record->image_path)$block['image_path'] = env('APP_URL').$record->image_path;
            $blocks = [$block];
            $block = self::getNotes($record,'calentamiento');
            if($block)$blocks[] = $block;
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
                    $block = self::getNotes($record,'sin_content');
                    if($block)$blocks[] = $block;
                } else {
                    $content = $content."\n".$record->con_content;
                    $block = self::getNotes($record,'con_content');
                    if($block)$blocks[] = $block;
                }
            }
            $objectKey = null;
            if ($objective == "strong") {
                if ($gender == "Male") {
                    $objectiveContent = $record->strong_male;
                    $objectKey = "strong_male";
                } else {
                    $objectiveContent = $record->strong_female;
                    $objectKey = "strong_female";
                }

            } else {
                if (isset($record[$objective])) {
                    $objectiveContent = $record[$objective];
                    $objectKey = $objective;
                } else {
                    $objectiveContent = "";
                }
            }
            $weekday = strtolower(date('l', strtotime($publishDate)));
            if (strpos($workoutFilter, 'Extra')!==false ) {
                if($sinContent){
                    $content = $content . "\n" . $record->extra_sin;
                    $block = self::getNotes($record,'extra_sin');
                    if($block)$blocks[] = $block;
                }else {
                    $content = $content . "\n" . $objectiveContent;
                    if($objectKey ){
                        $block = self::getNotes($record,$objectKey);
                        if($block)$blocks[] = $block;
                    }
                }
            }

            if (strpos($workoutFilter, 'Activo')!==false) {
                $content = $content . "\n" .$record->activo;
                $block = self::getNotes($record,'activo');
                if($block)$blocks[] = $block;
            }
            if (strpos($workoutFilter, 'Blog')!==false) {
                $blog = true;
                if($record->blog){
                    $content = $record->blog;
                    $blocks = [['content'=>$record->blog]];    
                }else{
                    $content = null;
                    $blocks = [];
                }
            }

            $spanishDate = ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A, %d de %B del %Y", strtotime($publishDate))));
            $spanishShortDate = ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A, %d de %B", strtotime($publishDate))));
            if($customerId){
                $customer = Customer::find($customerId);
                if($customer){
                    $content = str_ireplace("{name}", $customer->first_name, $content);
                    foreach($blocks as $index => $block){
                        if(isset($block['content']))$blocks[$index]['content'] = str_ireplace("{name}", $customer->first_name, $block['content']);
                        if(isset($block['note']))$blocks[$index]['note'] = str_ireplace("{name}", $customer->first_name, $block['note']);
                    }        
                }
            }
            $whatsapp = $content;
            $dashboard = self::replaceDashboard($content);
            foreach($blocks as $index => $block){
                if(isset($block['content']))$blocks[$index]['content'] = self::replaceDashboard($block['content']);
                if(isset($block['note']))$blocks[$index]['note'] = self::replaceDashboard($block['note']);
            }
            $content = self::replace($content,$customerId);
            $whatsapp = self::replaceWhatsapp($whatsapp);
            if($content)return ['date' => $spanishDate,'short_date' => $spanishShortDate, 'content' => $content, 'whatsapp' => $whatsapp,'dashboard'=>$dashboard,'blog'=>$blog,'blocks'=>$blocks];
        }    
        return null;
    }
}