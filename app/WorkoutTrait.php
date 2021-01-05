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
    private static function replaceDashboard($content,$position,$type){
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
    private static function getNotes($record, $slug,$customerId = null){
        $result = null;
        if($customerId){
            $customer = Customer::find($customerId);
        }
        if(isset($record->{$slug})&&$record->{$slug}){
            $result = ['content'=>$record->{$slug},'note'=>$record->{$slug.'_note'},'slug'=>$slug];
            if(isset($record->{$slug.'_timer_type'}) && $record->{$slug.'_timer_type'}){
                $result['timer_type'] = $record->{$slug.'_timer_type'};
                $result['timer_work'] = $record->{$slug.'_timer_work'};
                $result['timer_description'] = $record->{$slug.'_timer_description'};
                if($result['timer_work'] == null && in_array($result['timer_type'],['calentamiento','extra'])){
                    $result['timer_work'] = 30;
                }
                if($record->{$slug.'_timer_type'}=='tabata'){
                    $result['timer_round'] = $record->{$slug.'_timer_round'};
                    $result['timer_rest'] = $record->{$slug.'_timer_rest'};
                }
            }
        }
        if(Workout::UPDATE){
            $result = null;
            if(isset($record->{$slug.'_element'})&&$record->{$slug.'_element'}){
                if(is_array($record->{$slug.'_element'}))$lines = $record->{$slug.'_element'};
                else $lines = unserialize($record->{$slug.'_element'});
                if(isset($lines[0]) && $lines[0]['tag']!="h2"){
                    $title = self::getTitleFromColumn($slug);
                    if($title)$lines = array_merge([['tag'=>'h2','content'=>$title]],$lines);
                }
                foreach($lines as $index=>$line){
                    if(isset($line['video']) && $line['video']['id']){
                        $lines[$index]['video'] = self::findShortcode($line['video']['id'],$customerId);
                        $lines[$index]['line'] = $line;
                    }
                    if(isset($line['before_content']) ){
                        if(is_array($line['before_content'])){
                            if(isset($customer))$line['before_content']['content'] = str_ireplace("{name}", $customer->first_name, $line['before_content']['content']);
                            if($lines[$index]['video']){
                                $lines[$index]['before_content'] = str_replace("@@multipler@@", round($line['before_content']['multipler'] * $lines[$index]['video']['original_multipler']),$line['before_content']['content']);
                            }else{
                                $lines[$index]['before_content'] = str_replace("@@multipler@@", $line['before_content']['multipler'],$line['before_content']['content']);
                            }
                        }else{
                            if(isset($customer))$lines[$index]['before_content'] = str_ireplace("{name}", $customer->first_name, $line['before_content']);
                        }
                    }
                    if(isset($line['after_content'])){
                        if(is_array($line['after_content'])){
                            if(isset($customer))$line['after_content']['content'] = str_ireplace("{name}", $customer->first_name, $line['after_content']['content']);                        
                            if($lines[$index]['video']){
                                $lines[$index]['after_content'] = str_replace("@@multipler@@", round($line['after_content']['multipler'] / $lines[$index]['video']['multipler']),$line['after_content']['content']);
                            }else{
                                $lines[$index]['after_content'] = str_replace("@@multipler@@", $line['after_content']['multipler'],$line['after_content']['content']);
                            }
                        }else{
                            if(isset($customer))$lines[$index]['after_content'] = str_ireplace("{name}", $customer->first_name, $line['after_content']);
                        }
                    } 
                    if(isset($line['content'])){
                        if(isset($customer))$lines[$index]['content'] = str_ireplace("{name}", $customer->first_name, $line['content']);
                    }
                }
                $result = ['content'=>$lines,'slug'=>$slug];
                if(isset($record->{$slug.'_note'})){
                    $result['note'] = $record->{$slug.'_note'};
                }
                if(isset($record->{$slug.'_timer_type'}) && $record->{$slug.'_timer_type'}){
                    $result['timer_type'] = $record->{$slug.'_timer_type'};
                    $result['timer_work'] = $record->{$slug.'_timer_work'};
                    $result['timer_description'] = $record->{$slug.'_timer_description'};
                    if($result['timer_work'] == null && in_array($result['timer_type'],['calentamiento','extra'])){
                        $result['timer_work'] = 30;
                    }
                    if($record->{$slug.'_timer_type'}=='tabata'){
                        $result['timer_round'] = $record->{$slug.'_timer_round'};
                        $result['timer_rest'] = $record->{$slug.'_timer_rest'};
                    }
                }
            }
        }
        return $result;
    }
    protected static function  getTitleFromColumn($column){
        switch($column){
            case 'calentamiento':
                $title = "A. Calentamiento";
            break;
            case 'con_content':
                $title = "B. Workout del día";
            break;
            case 'sin_content':
                $title = "B. Workout del día";
            break;
            case 'extra_sin':
                $title = "C. Extra";
            break;
            case 'strong_male':
                $title = "C. Extra";
            break;
            case 'strong_female':
                $title = "C. Extra";
            break;
            case 'fit':
                $title = "C. Extra";
            break;
            case 'cardio':
                $title = "C. Extra";
            break;
            case 'activo':
                $title = "B. Workout del día";
            break;
            case 'blog':
                $title = "Blog";
            break;
            default:                        
                $title = false;
        }
        return $title;
    }
    private static function findSendableContent($record,$publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId=null){
        $weekday = strtolower(date('l', strtotime($publishDate)));
        if (isset($workoutCondition[$weekday]) && $workoutCondition[$weekday]) {
            $block = ['content'=>$record->comentario,'slug'=>'comentario'];
            if($record->image_path)$block['image_path'] = env('APP_URL').$record->image_path;
            $blocks = [$block];
            $block = self::getNotes($record,'calentamiento');
            if($block)$blocks[] = $block;
            $content = $record->comentario."\n";
            $title = self::getTitleFromColumn('calentamiento');
            if($title && strpos($record->calentamiento,'{h2}')===false)$content = $content."\n"."{h2}$title{/h2}";
            $content = $content."\n".$record->calentamiento;
            $sinContent = false;
            $blog = false;
            $workoutFilter = $workoutCondition[$weekday];
            if ($weightsCondition == 'sin pesas') {
                $sinContent = true;
            }
            if (strpos($workoutFilter, 'Workout')!==false) {
                if ($sinContent) {
                    $title = self::getTitleFromColumn('sin_content');
                    if($title && strpos($record->sin_content,'{h2}')===false)$content = $content."\n"."{h2}$title{/h2}";
                    $content = $content."\n".$record->sin_content;
                    $block = self::getNotes($record,'sin_content');
                    if($block)$blocks[] = $block;
                } else {
                    $title = self::getTitleFromColumn('con_content');
                    if($title && strpos($record->con_content,'{h2}')===false)$content = $content."\n"."{h2}$title{/h2}";
                    $content = $content."\n".$record->con_content;
                    $block = self::getNotes($record,'con_content');
                    if($block)$blocks[] = $block;
                }
            }
            $objectKey = null;
            if ($objective == "strong") {
                if ($gender == "Male") {
                    $objectTitle = self::getTitleFromColumn('strong_male');
                    $objectiveContent = $record->strong_male;
                    $objectKey = "strong_male";
                } else {
                    $objectTitle = self::getTitleFromColumn('strong_female');
                    $objectiveContent = $record->strong_female;
                    $objectKey = "strong_female";
                }

            } else {
                if (isset($record[$objective])) {
                    $objectTitle = self::getTitleFromColumn($objective);
                    $objectiveContent = $record[$objective];
                    $objectKey = $objective;
                } else {
                    $objectiveContent = "";
                }
            }
            $weekday = strtolower(date('l', strtotime($publishDate)));
            if (strpos($workoutFilter, 'Extra')!==false ) {
                if($sinContent){
                    $title = self::getTitleFromColumn('extra_sin');
                    if($title && strpos($record->extra_sin,'{h2}')===false)$content = $content."\n"."{h2}$title{/h2}";
                    $content = $content . "\n" . $record->extra_sin;
                    $block = self::getNotes($record,'extra_sin');
                    if($block)$blocks[] = $block;
                }else {
                    if(isset($objectTitle) && $objectTitle && strpos($objectiveContent,'{h2}')===false)$content = $content."\n"."{h2}$objectTitle{/h2}";
                    $content = $content . "\n" . $objectiveContent;
                    if($objectKey ){
                        $block = self::getNotes($record,$objectKey);
                        if($block)$blocks[] = $block;
                    }
                }
            }

            if (strpos($workoutFilter, 'Activo')!==false) {
                $title = self::getTitleFromColumn('activo');
                if($title){
                    if(strpos($record->activo,'{h2}')===false)$content = $content."\n"."{h2}$title{/h2}";
                }
                $content = $content . "\n" .$record->activo;
                $block = self::getNotes($record,'activo');
                if($block)$blocks[] = $block;
            }
            if (strpos($workoutFilter, 'Blog')!==false) {
                $blog = true;
                if($record->blog){
                    if(strpos($record->blog,'{h2}')===false){
                        $title = self::getTitleFromColumn('blog');
                        $content = "{h2}$title{/h2}\n".$record->blog;
                    }
                    $block = ['content'=>$record->blog,'timer_type'=>$record->blog_timer_type,'timer_work'=>$record->blog_timer_work,'timer_rest'=>$record->blog_timer_rest,'timer_round'=>$record->blog_timer_round,'timer_description'=>$record->blog_timer_description];
                    if($record->image_path)$block['image_path'] = env('APP_URL').$record->image_path;
                    $blocks = [$block];
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
            $dashboard = self::replaceDashboard($content,0,'introduce');
            foreach($blocks as $index => $block){
                if(isset($block['content']) ){
                    if(isset($block['slug'])){
                        $title = self::getTitleFromColumn($block['slug']);
                    }
                    $result = self::replaceDashboard($block['content'],$index,'content');
                    if(isset($title)){
                        if(isset($result[0]['tag']) && $result[0]['tag'] != 'h2'){
                            if($title)$result = array_merge([['tag'=>'h2','content'=>$title]],$result);
                        }
                    }
                    $blocks[$index]['content'] = $result;
                }
                if(isset($block['note'])){
                    $results = self::replaceDashboard($block['note'],$index,'note');
                    if(isset($result[0]['tag']) && $result[0]['tag'] != 'h2'){
                        $results = array_merge([['tag'=>'h2','content'=>'Notas']],$results);
                    }
                    $blocks[$index]['note'] = $results;
                }
            }
            $content = self::replace($content,$customerId);
            $whatsapp = self::replaceWhatsapp($whatsapp);
            if($content)return ['date' => $spanishDate,'short_date' => $spanishShortDate, 'content' => $content, 'whatsapp' => $whatsapp,'dashboard'=>$dashboard,'blog'=>$blog,'blocks'=>$blocks];
        }    
        return null;
    }
    public function convertContent($content){
        $pattern = '/\{button +[".\'](.*)[".\']\}([\s\S]*)\{\/button\}/';
        $notes = preg_match($pattern, $content,$noteKeywords);
        if($notes){
            $content = str_ireplace($noteKeywords[0], '<spectioalButton/>', $content);
        }
        $lines = explode("\n",$content);
        $results = [];
        $shortcodes = Shortcode::where('status', '=', 'Active')->get();
        $multiplerPattern = '/\#\d{1,2}/';
        foreach($lines as $line){
            if($notes && strpos($line,'<spectioalButton/>')!==false){
                $line = str_ireplace("<spectioalButton/>", "&&", $line);
                $contents = explode('&&',$line);
                $results[] = ['tag'=>'modal','before_content'=>$contents[0],'after_content'=>$contents[1],'title'=>$noteKeywords[1],'body'=>$noteKeywords[2]];
            }else if(strpos($line,'{h1}') || strpos($line,'{/h1}')){
                $line = str_ireplace("{h1}", "", $line);
                $line = str_ireplace("{/h1}", "", $line);
                $results[] = ['tag'=>'h1','content'=>$line];
            }else if(strpos($line,'{h2}') || strpos($line,'{/h2}')){
                $line = str_ireplace("{h2}", "", $line);
                $line = str_ireplace("{/h2}", "", $line);
                $results[] = ['tag'=>'h2','content'=>$line];
            }else {
                $video=null;
                $afterContent="";
                foreach ($shortcodes as $shortcode) {
                    if(strpos($line,"{{$shortcode->name}}")!==false){
                        $line = str_replace("{{$shortcode->name}}", "&&", $line);
                        $video=[
                            'name'=>$shortcode->name,
                            'id'=>$shortcode->id,
                            'url'=>$shortcode->video_url,
                            'time'=>$shortcode->time,
                            'level'=>$shortcode->level,
                            'alternate_a'=>null,
                            'multipler_a'=>$shortcode->multipler_a,
                            'alternate_b'=>null,
                            'multipler_b'=>$shortcode->multipler_b,
                        ];
                        if($shortcode->alternate_a){
                            $alternativeA = Shortcode::find($shortcode->alternate_a);
                            $video['alternate_a'] = [
                                'name'=>$alternativeA->name,
                                'id'=>$alternativeA->id,
                                'url'=>$alternativeA->video_url,
                                'time'=>$alternativeA->time,
                                'level'=>$alternativeA->level,    
                            ];
                        }
                        if($shortcode->alternate_b){
                            $alternativeB = Shortcode::find($shortcode->alternate_b);
                            $video['alternate_b'] = [
                                'name'=>$alternativeB->name,
                                'id'=>$alternativeB->id,
                                'url'=>$alternativeB->video_url,
                                'time'=>$alternativeB->time,
                                'level'=>$alternativeB->level,    
                            ];
                        }
                        $contents = explode('&&',$line);
                        $line = $contents[0];
                        $check = preg_match($multiplerPattern, $line,$keywords);
                        if($check){
                            $multipler = substr($keywords[0],1);
                            $line = str_replace($keywords[0],"@@multipler@@",$line);
                            $line = ['content'=>$line,'multipler'=>$multipler];
                        }            
                        $afterContent = $contents[1];
                        $check = preg_match($multiplerPattern, $afterContent,$keywords);
                        if($check){
                            $multipler = substr($keywords[0],1);
                            $afterContent = str_replace($keywords[0],"@@multipler@@",$afterContent);
                            $afterContent = ['content'=>$afterContent,'multipler'=>$multipler];
                        }      
                        break;      
                    }
                }
                if($line !== ""||$video!=null)$results[] = ['tag'=>'p','before_content'=>$line,'after_content'=>$afterContent,'video'=>$video];
            }
        }
        return $results;
    }
    public static function convertArray($lines,$column, $email = true,$customerId=null){
        if(!is_array($lines)) $lines = unserialize($lines);
        $result = "";
        if($customerId){
            $customer = Customer::find($customerId);
        }
        switch($column){
            case 'blog':case 'comentario':
            break;
            default:
            if(isset($lines[0]['tag']) && $lines[0]['tag'] != 'h2'){
                $result .= "<h2>".self::getTitleFromColumn($column)."</h2>";
            }
        }
        if(is_array($lines)){
            foreach($lines as $line){
                switch($line['tag']){
                    case "modal":
                        if(!$email) $result .= $line['before_content']."<button>".$line['title']."</button>".$line['after_content'];
                        break;
                    case "h2":
                        if(isset($customer))$content = str_ireplace("{name}", $customer->first_name, $line['content']);
                        $result .= "<h2>".$content."</h2>";
                        break;
                    case "h1":    
                        if(isset($customer))$content = str_ireplace("{name}", $customer->first_name, $line['content']);
                        $result .= "<h1>".$content."</h1>";
                        break;
                    case "p":    
                        $beforeContent = $line['before_content'];
                        if(is_array($beforeContent)){
                            $multipler = $beforeContent['multipler'];
                            $beforeContent = str_replace('@@multipler@@', $multipler, $beforeContent['content']);
                        }
                        if(isset($customer))$beforeContent = str_ireplace("{name}", $customer->first_name, $beforeContent);
                        $afterContent = $line['after_content'];
                        if(is_array($afterContent)){
                            $multipler = $afterContent['multipler'];
                            if(isset($customer))$afterContent['content'] = str_ireplace("{name}", $customer->first_name, $afterContent['content']);
                        }
                        if(isset($customer))$afterContent = str_ireplace("{name}", $customer->first_name, $afterContent);
                        if(isset($line['video'])){
                            if($email){
                                $url = env('APP_URL').'/api/customers/link?shortcode_id='.$line['video']['id'];
                                if($customerId)$url = $url.'&&customer_id='.$customerId;            
                                $result .= "<p>".$beforeContent."<a href=".$url.">".$line['video']['name']."</a>".$afterContent."</p>";
                            }
                            else $result .= "<p>".$beforeContent."<button>".$line['video']['name']."</button>".$afterContent."</p>";
                        }else {
                            $result .= "<p>".$beforeContent.$afterContent."</p>";                        
                        }
                        break;
                    }
            }
        }else{
            $result .= "";
        }
        return [$result];
    }
    public static function findSendableContentFromArray($record,$publishDate, $workoutCondition, $weightsCondition, $objective, $gender,$customerId){
        $weekday = strtolower(date('l', strtotime($publishDate)));
        $content = '';
        if (isset($workoutCondition[$weekday]) && $workoutCondition[$weekday]) {
            $block = self::getNotes($record,'comentario',$customerId);
            if($record->image_path)$block['image_path'] = env('APP_URL').$record->image_path;
            $blocks = [$block];
            $block = self::getNotes($record,'calentamiento',$customerId);
            if($block)$blocks[] = $block;
            // $content = self::convertArray($record->comentario_element,'comentario',true,$customerId)[0].self::convertArray($record->calentamiento_element,'calentamiento',true,$customerId)[0];
            $sinContent = false;
            $blog = false;
            $workoutFilter = $workoutCondition[$weekday];
            if ($weightsCondition == 'sin pesas') {
                $sinContent = true;
            }
            if (strpos($workoutFilter, 'Workout')!==false) {
                if ($sinContent) {
                    // $content = $content.self::convertArray($record->sin_content_element,'sin_content',true,$customerId)[0];
                    $block = self::getNotes($record,'sin_content',$customerId);
                    if($block)$blocks[] = $block;
                } else {
                    // $content = $content.self::convertArray($record->con_content_element,'con_content',true,$customerId)[0];
                    $block = self::getNotes($record,'con_content',$customerId);
                    if($block)$blocks[] = $block;
                }
            }
            $objectKey = null;
            if ($objective == "strong") {
                if ($gender == "Male") {
                    $objectiveContent = self::convertArray($record->strong_male_element,'strong_male',true,$customerId)[0];
                    $objectKey = "strong_male";
                } else {
                    $objectiveContent = self::convertArray($record->strong_female_element,'strong_female',true,$customerId)[0];
                    $objectKey = "strong_female";
                }

            } else {
                if (isset($record[$objective])) {
                    $objectiveContent = self::convertArray($record[$objective.'_element'],$objective,true,$customerId)[0];
                    $objectKey = $objective;
                } else {
                    $objectiveContent = "";
                }
            }
            $weekday = strtolower(date('l', strtotime($publishDate)))[0];
            if (strpos($workoutFilter, 'Extra')!==false ) {
                if($sinContent){
                    // $content = $content.self::convertArray($record->extra_sin_element,'extra_sin',true,$customerId)[0];
                    $block = self::getNotes($record,'extra_sin',$customerId);
                    if($block)$blocks[] = $block;
                }else {
                    // $content = $content . "\n" . $objectiveContent;
                    if( $objectKey ){
                        $block = self::getNotes($record,$objectKey,$customerId);
                        if($block)$blocks[] = $block;
                    }
                }
            }

            if (strpos($workoutFilter, 'Activo')!==false) {
                // $content = $content.self::convertArray($record->activo_element,'activo',true,$customerId)[0];
                $block = self::getNotes($record,'activo',$customerId);
                if($block)$blocks[] = $block;
            }
            if (strpos($workoutFilter, 'Blog')!==false) {
                $blog = true;
                if($record->blog){
                    // $content = self::convertArray($record->blog_element,'blog',true,$customerId)[0];
                    $block = self::getNotes($record,'blog',$customerId);
                    if($record->image_path)$block['image_path'] = env('APP_URL').$record->image_path;
                    $blocks = [$block];
                }else{
                    // $content = null;
                    $blocks = [];
                }
            }

            $spanishDate = ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A, %d de %B del %Y", strtotime($publishDate))));
            $spanishShortDate = ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A, %d de %B", strtotime($publishDate))));
            if($customerId){
                $customer = Customer::find($customerId);
                if($customer){
                    // $content = str_ireplace("{name}", $customer->first_name, $content);
                    foreach($blocks as $index => $block){
                        if(isset($block['note']))$blocks[$index]['note'] = str_ireplace("{name}", $customer->first_name, $block['note']);
                    }        
                }
            }
            foreach($blocks as $index => $block){
                if(isset($block['note'])){
                    $results = array_merge([['tag'=>'h2','content'=>'Notas']],self::replaceDashboard($block['note'],$index,'note'));
                    $blocks[$index]['note'] = $results;
                }
                if(isset($block['content'])){
                    foreach($block['content'] as $line){
                        if(isset($line['tag'])){
                            switch($line['tag']){
                                case 'h1':
                                    $content = $content."<h1>".$line['content']."</h1>";
                                    break;
                                case 'h2':
                                    $content = $content."<h2>".$line['content']."</h2>";
                                    break;
                                case 'p':
                                    $content = $content."<p>";
                                    if(isset($line['before_content']) && $line['before_content']){
                                        $content = $content.$line['before_content'];
                                    }
                                    if(isset($line['video']) && $line['video']){
                                        $url = env('APP_URL').'/api/customers/link?shortcode_id='.$line['video']['id'];
                                        if($customerId)$url = $url.'&&customer_id='.$customerId;
                                        $content = $content."<a href='".$url."' target='_blank'>".$line['video']['name']."</a>";
                                    }
                                    if(isset($line['after_content']) && $line['after_content']){
                                        $content = $content.$line['after_content'];
                                    }
                                    $content = $content."</p>";
                                    break;
                            }                        
                        }
                    }
                }
            }
            if($content)return ['date' => $spanishDate,'short_date' => $spanishShortDate, 'content' => $content,'blog'=>$blog,'blocks'=>$blocks];
        }    
        return null;
    }
    private static function findShortcode($shortcodeId, $customerId){
        $shortcode = Shortcode::find($shortcodeId);
        $video=[
            'name'=>$shortcode->name,
            'id'=>$shortcode->id,
            'url'=>$shortcode->video_url,
            'time'=>$shortcode->time,
            'level'=>$shortcode->level,
            'instruction'=>$shortcode->instruction,
            'alternate_a'=>null,
            'multipler_a'=>$shortcode->multipler_a,
            'alternate_b'=>null,
            'multipler_b'=>$shortcode->multipler_b,
            'multipler'=>1,
            'original_multipler'=>1,
            'original_id'=>$shortcode->id,
        ];
        if($shortcode->alternate_a){
            $alternativeA = Shortcode::find($shortcode->alternate_a);
            $video['alternate_a'] = [
                'name'=>$alternativeA->name,
                'id'=>$alternativeA->id,
                'instruction'=>$alternativeA->instruction,
                'url'=>$alternativeA->video_url,
                'time'=>$alternativeA->time,
                'level'=>$alternativeA->level,    
                'multipler'=>$shortcode->multipler_a,
                'original_multipler'=>$shortcode->multipler_a,
            ];
        }
        if($shortcode->alternate_b){
            $alternativeB = Shortcode::find($shortcode->alternate_b);
            $video['alternate_b'] = [
                'name'=>$alternativeB->name,
                'id'=>$alternativeB->id,
                'instruction'=>$alternativeB->instruction,
                'url'=>$alternativeB->video_url,
                'time'=>$alternativeB->time,
                'level'=>$alternativeB->level,    
                'multipler'=>$shortcode->multipler_b,
                'original_multipler'=>$shortcode->multipler_b,
            ];
        }
        if( $customerId ){
            $customerShortcode = CustomerShortcode::whereCustomerId($customerId)->whereShortcodeId($shortcodeId)->first();
            $change = false;
            if($customerShortcode){
                if($customerShortcode->alternate_id != $shortcodeId){
                    $diffDates = (time() - strtotime($customerShortcode->created_at->format('Y-m-d H:i:s')))/3600/24;
                    if($diffDates<$customerShortcode->alternate->time){
                        if($customerShortcode->alternate_id == $shortcode->alternate_a)$change = "a";
                        if($customerShortcode->alternate_id == $shortcode->alternate_b)$change = "b";
                    }
                }
            }
            if(!$change){
                $customer = Customer::find($customerId);
                if($shortcode->level > $customer->current_condition){
                    if($alternativeA->level == $customer->current_condition){
                        $change = "a";
                    }
                    if($alternativeB->level == $customer->current_condition){
                        $change = "b";
                    }
                }
            }
            if($change){
                if($change == "a"){
                    $video['name']=$alternativeA->name;
                    $video['id']=$alternativeA->id;
                    $video['url']=$alternativeA->video_url;
                    $video['time']=$alternativeA->time;
                    $video['level']=$alternativeA->level;
                    $video['instruction']=$alternativeA->instruction;
                    $video['alternate_a'] = [
                        'name'=>$shortcode->name,
                        'id'=>$shortcode->id,
                        'instruction'=>$shortcode->instruction,
                        'url'=>$shortcode->video_url,
                        'time'=>$shortcode->time,
                        'level'=>$shortcode->level,    
                        'multipler'=>1,
                    ];
                    if($shortcode->multipler_a){
                        $video['multipler'] = $shortcode->multipler_a;
                        $video['multipler_a'] = 1;
                        $video['original_multipler'] = $shortcode->multipler_a;
                    }
                }
                if($change == "b"){
                    $video['name']=$alternativeB->name;
                    $video['id']=$alternativeB->id;
                    $video['url']=$alternativeB->video_url;
                    $video['time']=$alternativeB->time;
                    $video['level']=$alternativeB->level;
                    $video['instruction']=$alternativeB->instruction;
                    $video['alternate_b'] = [
                        'name'=>$shortcode->name,
                        'id'=>$shortcode->id,
                        'instruction'=>$shortcode->instruction,
                        'url'=>$shortcode->video_url,
                        'time'=>$shortcode->time,
                        'level'=>$shortcode->level,    
                        'multipler'=>1,
                    ];
                    if($shortcode->alternate_b){
                        $video['multipler'] = $shortcode->multipler_b;
                        $video['multipler_b'] = 1;
                        $video['original_multipler'] = $shortcode->multipler_b;
                    }
                }
            }
        }
        return $video;
    }
}