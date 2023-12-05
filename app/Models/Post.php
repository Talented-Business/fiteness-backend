<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Jobs\CompleteUploadJob;

class Post extends Model
{
    protected $table = 'posts';
    protected $fillable = ['customer_id','content','activity_id','location','tag_followers','type','object_id'];
    protected $lastId;
    protected $casts = [
        'tag_followers' => 'array',
        'json_content' => 'array',
    ];
    //   (\@\{\$([^$]+)\$\}\(([0-9]+)\))
    //   \@\[(\w.+\w)\]\(([0-9]*)\) 
    public static function validateRules($id=null){
        return array(
            'content'=>'max:5000',
            'medias'=>'array|max:50',
            'medias.*'=>'image|max:209715200',
            'tag_followers'=>'array|max:191',
            'location'=>'max:191',
        );
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customer_id');
    }
    public function medias()
    {
        return $this->hasMany('App\Models\Media', 'post_id');
    }
    public function readingCustomers(){
        return $this->belongsToMany('App\Customer','reading_posts','post_id','customer_id')->wherePivot('status','completed');
    }
    public function uploadMedias($files){
        foreach($files as $file){
            $media = new Media;
            $media->post_id = $this->id;
            $media->uploadSingle($file);
        }
        CompleteUploadJob::dispatch($this->id);
    }
    public function assignFrontSearch($request){
        $this->customer_id = $request->customer_id;
        if($request->post_id)$this->lastId = $request->post_id;
    }
    public function search($user=null){
        $where = self::with(['customer','medias'])->whereStatus(1);
        if($this->lastId)$where->where('id','<',$this->lastId);
        $where->where('status',1);
        $where->whereCustomerId($this->customer_id);
        $posts =  $where->orderBy('id','desc')->limit(3)->get();
        foreach($posts as $index=>$post){
            $post->extend(null,$user);
        }
        return $posts;
    }
    public function extend($condition=null, $user=null){
        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        $tagFollowers = [];
        if($this->customer){
            $this->customer->getAvatar();
            if($user)$this->customer->getSocialDetails($user->customer->id);
        }
        if(is_array($this->tag_followers)){
            foreach($this->tag_followers as $customerId){
                $customer = \App\Customer::find($customerId);
                $customer->getAvatar();
                $tagFollowers[] = $customer;
            }
        }
        if(is_array($this->json_content)){
            $contentFollowers = [];
            foreach($this->json_content as $index=>$line){
                foreach($line['ids'] as $customerId){
                    $customer = \App\Customer::find($customerId);
                    $contentFollowers[] = $customer;
                }
            }
            $this->contentFollowers = $contentFollowers;
        }
        $this->tagFollowers = $tagFollowers;
        $this->medias;
        if($condition && $condition['from_id']>-1){
            list($previousComments, $viewComments, $nextComments) = Comment::findByCondition($condition, $user);
            $this->previousCommentsCount = $previousComments->count();
            $this->comments = $viewComments;//->get()->toArray();
            $this->nextCommentsCount = $nextComments->count();
        }else{
            if($condition&&isset($condition['count'])&&$condition['count']==-1){
                $this->previousCommentsCount = 0;
                $viewComments = Comment::wherePostId($this->id)->where('level1', 0)->orderBy('level0')->get();
                foreach($viewComments as $index=>$comment){
                    $comment->customer->getAvatar();
                    $likes = Like::whereActivityId($comment->activity_id)->get();
                    $comment->likesCount = $likes->count();
                    $comment->like=false;
                    $comment->extends();
                    if(isset($user->customer)){
                        $comment->getLike($user);
                    }
                }                    
                $this->comments = $viewComments;//->get()->toArray();
            }else{
                $latestComment = Comment::with('customer')->wherePostId($this->id)->where('level1',0)->orderBy('level0','desc')->first();
                if($latestComment){
                    $comments = Comment::wherePostId($this->id)->where('level1',0)->get();
                    // $replyComments = Comment::whereParentActivityId($latestComment->activity_id)->orderBy('id')->get();
                    // $this->comments = $replyComments->prepend($latestComment);
                    $latestComment->customer->getAvatar();
                    $likes = Like::whereActivityId($latestComment->activity_id)->get();
                    $latestComment->likesCount = $likes->count();
                    $latestComment->like=false;
                    $latestComment->extends();
                    if(isset($user->customer)){
                        $like = Like::whereActivityId($latestComment->activity_id)->whereCustomerId($user->customer->id)->first();
                        $latestComment->like = $like?true:false;    
                    }
                    $this->comments = [$latestComment];
                    $this->previousCommentsCount = $comments->count()-1;
                }else{
                    $this->comments = [];
                    $this->previousCommentsCount = 0;
                }
            }
            $this->nextCommentsCount = 0;
        }
        $this->commentsCount = Comment::wherePostId($this->id)->count();
        $likes = Like::whereActivityId($this->activity_id)->get();
        $this->likesCount = $likes->count();
        if($user && $user->customer){
            $like = Like::whereActivityId($this->activity_id)->whereCustomerId($user->customer->id)->first();
            $this->like = $like?true:false;
        }
        //if type is shop, it is avatar
        //if type is shop, event, blogs, benchmark, get medias from their images, get content from source
        switch($this->type){
            case 'workout':
                $workoutComment = WorkoutComment::find($this->object_id);
                if ($workoutComment) {
                    $this->content = $workoutComment->content;
                    if($workoutComment->dumbells_weight)$this->dumbells_weight = $workoutComment->dumbells_weight;
                    $this->json_content = $this->convertJson($workoutComment->content);
                    $this->contentFollowers = [];
                    $this->workout_spanish_date = ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A, %d de %B del %Y", strtotime($workoutComment->publish_date))));
                    $this->workout_spanish_short_date = ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A, %d de %B", strtotime($workoutComment->publish_date))));
                }
                break;
            case 'shop':
                $company = \App\Company::find($this->object_id);
                if ($company) {
                    $this->title = $company->name;
                    $this->content = $company->description;
                    $this->json_content = $this->convertJson($company->description);
                    $this->contentFollowers = [];
                    $this->shopUsername = $company->username;
                    $this->shopLogo = ['small'=>config('app.url').'/storage/'.$company->logo];
                    unset($this->medias);
                    $this->medias = [];
                    if($company->post_image_id){
                        $media = Media::find($company->post_image_id);
                        $this->medias = [$media];
                    }
                }
                //latest product image
                // $image = \App\ProductImage::whereHas('product',function($query) use ($company){
                //     $query->whereCompanyId($company->id);
                // })->orderBy('id','desc')->first();
                // if($image){
                //     if($image->width==null){
                //         $data = getimagesize(config('app.url').'/storage/'.$image->image);
                //         if(isset($data[0])){
                //             $image->width = $data[0];
                //             $image->height = $data[1];
                //             $image->save();
                //         }                        
                //     }
                //     unset($this->medias);
                //     $medias = [['url'=>config('app.url').'/storage/'.$image->image,'post_id'=>$this->id,'type'=>'image','width'=>$image->width,'height'=>$image->height]];
                //     $this->medias = $medias;
                // }
                break;
            case 'blog':
                $blog = \App\Event::find($this->object_id);
                if ($blog) {
                    $this->title = $blog->title;
                    $this->content = "";//$blog->description;
                    $this->contentType = "html";
                    if($blog->image){
                        if($blog->image_width==null){
                            $path = getcwd().'/storage/'.$blog->image;
                            $data = getimagesize($path);
                            if(isset($data[0])){
                                $blog->image_width = $data[0];
                                $blog->image_height = $data[1];
                                $blog->save();
                            }                        
                        }
                        unset($this->medias);
                        $medias = [['url'=>config('app.url').'/storage/'.$blog->image,'post_id'=>$this->id,'type'=>'image','width'=>$blog->image_width,'height'=>$blog->image_height]];
                        $this->medias = $medias;
                    }
                }
                break;
            case 'benchmark':
                $benchmark = \App\Benchmark::find($this->object_id);
                if ($benchmark) {
                    $this->title = $benchmark->title;
                    $this->content = $benchmark->description;
                    $this->json_content = $this->convertJson($benchmark->description);
                    $this->contentFollowers = [];
                    if($benchmark->image){
                        if($benchmark->image_width==null){
                            $data = getimagesize(config('app.url').'/storage/'.$benchmark->image);
                            if(isset($data[0])){
                                $benchmark->image_width = $data[0];
                                $benchmark->image_height = $data[1];
                                $benchmark->save();
                            }                        
                        }
                        unset($this->medias);
                        $medias = [['url'=>config('app.url').'/storage/'.$benchmark->image,'post_id'=>$this->id,'type'=>'image','width'=>$benchmark->image_width,'height'=>$benchmark->image_height]];
                        $this->medias = $medias;
                    }
                }
                break;
            case 'evento':
                $evento = \App\Models\Evento::find($this->object_id);
                if ($evento) {
                    $this->title = $evento->title;
                    $this->content = $evento->description;
                    $this->contentType = "html";
                    if(count($evento->medias)>0){
                        unset($this->medias);
                        $evento->getImages();
                        $this->medias = $evento->images;
                        foreach($this->medias as $media){
                            $media->post_id = $this->id;
                        }
                    }
                }
                break;
            case 'join':
                $customer = \App\Customer::find($this->object_id);
                if ($customer) {
                    $customer->getAvatar();
                    $customer->chat_id = $customer->user->chat_id;
                    unset($customer->user);
                    unset($this->customer);
                    $this->customer = $customer;
                }
                break;
        }
    }
    private function convertJson($content){
        $lines = explode("\n",$content);
        $json = [];
        foreach($lines as $line){
            $json[] = ['content'=>$line];
        }
        return $json;
    }
}
