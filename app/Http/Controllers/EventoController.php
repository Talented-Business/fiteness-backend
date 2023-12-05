<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Evento;
use App\Models\EventoComment;
/**
 * @group Evento   
 *
 * APIs for managing  evento
 */

class EventoController extends Controller
{
    /**
     * create a evento.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function store(Request $request)
    {
        $user = $request->user('api');
        if($user->can('events')){
            $validator = Validator::make($request->all(), Evento::validateRules());
            if ($validator->fails()) {
                return response()->json(array('status'=>'failed','errors'=>$validator->errors()),422);
            }
            $evento = new Evento;
            $evento->fill($request->input());
            $evento->save();
            if(isset($request->images)){
                $evento->uploadMedias($request->images);
                $evento->save();
            }
            return response()->json(array('status'=>'ok','evento'=>$evento));
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * update a evento.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function update($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('events')){
            $validator = Validator::make($request->all(), Evento::validateRules());
            if ($validator->fails()) {
                return response()->json(array('status'=>'failed','errors'=>$validator->errors()),422);
            }
            $evento = Evento::find($id);
            $oldImages = collect($evento->medias);
            if(isset($request->image_ids)){
                $imageIds = $request->image_ids;
                foreach($imageIds as $imageId){
                    $oldImages = $oldImages->reject(function($image,$key) use ( $imageId ){
                        return $image == $imageId;
                    });
                }
                $evento->medias = $imageIds;
            }
            if(isset($request->images)){
                $evento->uploadMedias($request->images);
            }
            $evento->fill($request->input());
            $evento->save();
            if($oldImages&&$oldImages->count()>0){
                $oldImages->each(function($id){
                    $media = \App\Models\Media::find($id);
                    Storage::disk('s3')->delete($media->src);
                    $media->delete();
                });
            }
            return response()->json(array('status'=>'ok','evento'=>$evento));
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * destroy a evento.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function destroy($id,Request $request){
        $user = $request->user('api');
        if($user->can('events')){
            $asset = Evento::find($id);
            if($asset){
                $destroy=Evento::destroy($id);
            }
            if ($destroy){
                $data=[
                    'status'=>'1',
                    'msg'=>'success'
                ];
            }else{
                $data=[
                    'status'=>'0',
                    'msg'=>'fail'
                ];
            }        
            return response()->json($data);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * show a evento.
     * 
     * This endpoint.
     * @authenticated
     * @urlParam evento integer required
     * @response {
     *  "id":9,
     *  "title":"title",
     *  "description":"description",
     *  "done_date":"2022/02/04",
     *  "latitude":"23.1231312",
     *  "longitude":"-123.12312312",
     *  "address":"address",
     *  "spanish_date":"spanish_date",
     *  "spanish_time":"spanish_time",
     *  "participants":6,
     *  "participant": true,  //false
     *  "commentsCount":8,
     *  "comments":[{comment}] // only level1=0
     * }
     */
    public function show($id,Request $request){
        $user = $request->user('api');
        $evento = Evento::find($id);
        $evento->getImages();
        $evento['created_date'] = date('M d, Y',strtotime($evento->done_date));
        if($evento->done_date){
            $dates = explode(' ',$evento->done_date);
            setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
            $evento['spanish_date'] = iconv('ISO-8859-2', 'UTF-8', strftime("%B %d, %Y ", strtotime($evento->done_date)));
            $evento['spanish_time'] = date("h:i a",strtotime($evento->done_date));
            $evento['participants'] = $evento->customers->count();
            $evento['date'] = $dates[0];
            $evento['datetime'] = substr($dates[1],0,5);
            if( $user && $user->customer ){
                $participant = false;
                foreach($evento->customers as $customer){
                    if($customer->id == $user->customer->id)$participant = true;
                }
                $evento['participant'] = $participant;
            }
            $evento['commentsCount'] = EventoComment::whereEventoId($id)->count();
            if($evento['commentsCount']>0){
                $comments = EventoComment::whereEventoId($id)->whereLevel1(0)->orderBy('level0')->get();
                foreach($comments as $comment){
                    $comment->extends();
                }
                $evento->comments = $comments;
            }else{
                $evento->comments = [];
            }
        }
        return response()->json($evento);
    }
    /**
     * search eventos.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function index(Request $request){
        $user = $request->user('api');
        if($user->can('events')){
            $evento = new Evento;
            $evento->assignSearch($request);
            return response()->json($evento->search());
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * find published eventos.
     * 
     * This endpoint.
     * @authenticated
     * @bodyParam pageSize integer required
     * @bodyParam pageNumber integer required
     * @response {
     * }
     */
    public function home(Request $request){
        $evento = new Evento;
        $evento->assignFrontSearch($request);
        $user = $request->user('api');
        if($user&&$user->customer)\App\Jobs\Activity::dispatch($user->customer);
        return response()->json($evento->search());
    }
    /**
     * disable a evento.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function disable($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('events')){
            $evento = Evento::find($id);
            if ($evento) {
                $evento->status = 'Draft';
                $evento->save();
                return response()->json(['success' => 'success']);
            }
            return response()->json(['error' => 'error'], 422);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * restore a evento.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function restore($id,Request $request)
    {
        $user = $request->user('api');
        if($user->can('events')){
            $evento = Evento::find($id);
            if ($evento) {
                $evento->status = 'Publish';
                $evento->save();
                return response()->json(['success' => 'success']);
            }
            return response()->json(['error' => 'error'], 422);
        }else{
            return response()->json(['status'=>'failed'],403);
        }
    }
    /**
     * get recent 3 eventos.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     * }
     */
    public function recent(){
        $items = Evento::whereStatus('Publish')->where('done_date','<',date("Y-m-d H:i:s"))->take(3)->orderBy('created_at','desc')->get();
        foreach($items as $index=> $evento){
            $items[$index]['created_date'] = date('M d, Y',strtotime($evento->created_at));
            $evento->category;
            $items[$index]['excerpt'] = $evento->extractExcerpt($evento->description);
            if($evento->image)  $evento->image = secure_url('storage/'.$evento->image);            
        }
        return response()->json($items);
    }
    /**
     * toggle attending on a evento.
     * 
     * This endpoint.
     * @authenticated
     * @urlParams id integer required
     * 
     * @response {
     *  "event":{evento}
     * }
     */
    public function toggleAttend($id,Request $request){
        $user = $request->user('api');
        $evento = Evento::find($id);
        if($user && $user->customer->id){
            $evento->toggleAttend($user->customer);
            return response()->json(['event'=>$evento]);
        }
        return response()->json(['status'=>'failed'],403);
    }
    /**
     * get random eventos and blogs, products.
     * 
     * This endpoint.
     * @authenticated
     * @response {
     *  "events":[{evento}],
     *  "news":[{blog}]
     *  "products":[{product}]
     * }
     */
    public function random(Request $request){
        $user = $request->user('api');
        $eventos = Evento::where('done_date',  '>=', date('Y-m-d') )->inRandomOrder()->limit(2)->get();
        foreach($eventos as $evento){
            $evento->getImages();
            $evento['spanish_date'] = iconv('ISO-8859-2', 'UTF-8', strftime("%B %d, %Y ", strtotime($evento->done_date)));
            $evento['spanish_time'] = date("j:i a",strtotime($evento->done_date));
        }
        $news = \App\Event::inRandomOrder()->limit(4)->get();
        foreach($news as $item){
            $item->category;
            if($item->image)  $item->image = secure_url('storage/'.$item->image);
        }
        $product = new \App\Product;
        $productImage = new \App\ProductImage;
        $products = $product->getRelatedItems($user->customer);
        foreach($products as $index=>$item){
            $product = \App\Product::find($item->id);
            $item->company_name = $product->company?$product->company->name:"";
            if(isset($item->image))$products[$index]->media_url = $productImage->getImageSize($item->image,"x-small");
        }
        return response()->json(['events'=>$eventos,'news'=>$news,'products'=>$products]);
    }
}