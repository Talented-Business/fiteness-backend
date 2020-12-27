<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Shortcode extends Model
{
    protected $fillable = ['name','link','time','level','alternate_a','multipler_a','alternate_b','multipler_b','instruction'];    
    private $pageSize;
    private $pageNumber;
    private $search;
    public static function validateRules($id=null){
        return array(
            'name'=>'required|max:255|unique:shortcodes,name,'.$id,
            'link'=>'max:255',
            'time'=>'nullable|integer',
            'level'=>'nullable|integer',
            'video'=>'mimetypes:video/x-m4v,video/mp4,video/3gpp',
            'alternate_a'=>'nullable|integer',
            'multipler_a'=>'nullable|numeric',
            'alternate_b'=>'nullable|integer',
            'multipler_b'=>'nullable|numeric',
        );
    }
    private static $searchableColumns = ['search'];
    public function search(){
        $where = Shortcode::where(function($query){
            if($this->search!=null){
                $query->Where('name','like','%'.$this->search.'%');
                $query->orWhere('link','like','%'.$this->search.'%');
            }
        });
        $currentPage = $this->pageNumber+1;
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });      
        $response = $where->orderBy('created_at', 'DESC')->paginate($this->pageSize);
        return $response;
    }
    public function assign($request){
        foreach($this->fillable as $property){
            if($request->exists($property)){
                $this->{$property} = $request->input($property);
            }
        }
    }
    public function assignSearch($request){
        foreach(self::$searchableColumns as $property){
            $this->{$property} = $request->input($property);
        }
        $this->pageSize = $request->input('pageSize');
        $this->pageNumber = $request->input('pageNumber');
    }
    public static function replace($content)
    {
        $lines = explode("\n",$content);
        $results = [];
        $shortcodes = Shortcode::where('status', '=', 'Active')->get();
        foreach($lines as $line){
            $youtube=null;
            foreach ($shortcodes as $shortcode) {
                if(strpos($line,"{{$shortcode->name}}")){
                    preg_match('/https:\/\/www.youtube.com\/watch\?v=(.*)/',$shortcode->link,$matches);
                    if(isset($matches[1])){
                        $line = str_replace("{{$shortcode->name}}", "", $line);
                        $youtube=['name'=>$shortcode->name,'vid'=>$matches[1]];
                    }else{
                        preg_match('/https:\/\/youtu.be\/(.*)/',$shortcode->link,$matches);
                        if(isset($matches[1])){
                            $line = str_replace("{{$shortcode->name}}", "", $line);
                            $youtube=['name'=>$shortcode->name,'vid'=>$matches[1]];
                        }
                    }
                }
            }
            $results[] = ['tag'=>'p','content'=>$line,'youtube'=>$youtube];
        }
        return $results;
    }
    public function uploadVideo($file){
        $year = date("Y");
        $month = date("m");    
        $fileExtension = $file->extension();
        $videoPath = 'shortcodes/'.$year.'/'.$month;
        $filename = $videoPath.'/'.$this->id.'.'.$fileExtension;
        $path = $file->storeAs('', #$path
        $filename, #$fileName
        ['disk'=>'s3', 'visibility'=>'public']);
        $this->video_url = \Storage::disk('s3')->url($path);
    }
    public function getMaximumFileUploadSize()  
    {  
        return min($this->convertPHPSizeToBytes(ini_get('post_max_size')), $this->convertPHPSizeToBytes(ini_get('upload_max_filesize')));  
    }  
    
    /**
    * This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
    * 
    * @param string $sSize
    * @return integer The value in bytes
    */
    private function convertPHPSizeToBytes($sSize)
    {
        //
        $sSuffix = strtoupper(substr($sSize, -1));
        if (!in_array($sSuffix,array('P','T','G','M','K'))){
            return (int)$sSize;  
        } 
        $iValue = substr($sSize, 0, -1);
        switch ($sSuffix) {
            case 'P':
                $iValue *= 1024;
                // Fallthrough intended
            case 'T':
                $iValue *= 1024;
                // Fallthrough intended
            case 'G':
                $iValue *= 1024;
                // Fallthrough intended
            case 'M':
                $iValue *= 1024;
                // Fallthrough intended
            case 'K':
                $iValue *= 1024;
                break;
        }
        return (int)$iValue;
    }    
}
