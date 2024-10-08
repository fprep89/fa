<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product\Product as Obj;
use App\Models\Product\Order;
use App\Models\Test\Group;
use App\Models\Test\Test;
use App\Models\Test\Mock;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\Product\Client;

class ProductController extends Controller
{
    /*
        Product Controller
    */

    public function __construct(){
        $this->app      =   'product';
        $this->module   =   'product';
        $this->cache_path =  '../storage/app/cache/product/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Obj $obj,Request $request)
    {
        $this->authorize('view', $obj);

        $search = $request->search;
        $item = $request->item;


        /* update in cache folder */
        if($request->refresh){
            $filename = 'index.'.$this->app.'.'.$this->module.'.json';
            $filepath = $this->cache_path.$filename;

            $objs = $obj->orderBy('created_at','desc')
                        ->get();  

            Cache::forever($filename,$objs);
            //file_put_contents($filepath, json_encode($objs,JSON_PRETTY_PRINT));
            
            foreach($objs as $obj){ 
                $filename = $obj->slug.'.json';
                $filepath = $this->cache_path.$filename;
                $obj->groups = $obj->groups;
                $obj->tests = $obj->tests()->orderBy('name','asc')->get();

                $test_ids = $obj->tests->pluck('id')->toArray();
                if(isset($obj->tests[0])){
                    $test_ids_all = $obj->tests[0]->category->tests->where('status',1)->pluck('id')->toArray();


                    $t=array();
                    foreach($test_ids_all as $ts)
                        if(!in_array($ts,$test_ids))
                        array_push($t, $ts); 
                    
                    shuffle($t);
                    $t= array_slice($t,0,6);
    

                    $related_tests = Test::whereIn('id',$t)->get();
                    $obj->related_tests = $related_tests;
                }else{
                    $obj->related_tests = null;
                }
                
                foreach($obj->tests as $test){
                $obj->tests->testtype = $test->testtype;
                 }

                foreach($obj->groups as $m=>$group){
                    $obj->groups->tests = $group->tests;
                    foreach($obj->groups->tests as $test){
                        $obj->groups->tests->testtype = $test->testtype;
                    }
                }
                Cache::forever($filepath,$obj);
                //file_put_contents($filepath, json_encode($obj,JSON_PRETTY_PRINT));
            }
           
            flash('Product Pages Cache Updated')->success();
        }
            
        if(subdomain()=='prep')
        $objs = $obj->sortable()->where('name','LIKE',"%{$item}%")
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records'));  
        else
        $objs = $obj->sortable()->where('name','LIKE',"%{$item}%")
                    ->where('client_slug',subdomain())
                    ->orderBy('created_at','desc')
                    ->paginate(config('global.no_of_records'));    
        
        $view = $search ? 'list': 'index';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('obj',$obj)
                ->with('app',$this);
    }


    /** PUBLIC LISTING
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function public(Obj $obj,Request $request)
    {

        $search = $request->search;
        $tag = $request->tag; 
        $item = $request->item;
        
        $client_slug = client('slug');

       

        if($request->get('refresh')){
            Cache::forget('products_'.$client_slug);
            Cache::forget('ptags_'.$client_slug);
        }
          
        $objs = Cache::get('products_'.$client_slug);
        
        if(!$objs && !$search)
            {
                $objs = $obj->where('name','LIKE',"%{$item}%")->where('client_slug',$client_slug)
                        
                        ->orderBy('created_at','desc')
                        ->get(); 
                 Cache::forever('products_'.$client_slug,$objs);  
            }elseif($search){
                $objs = $obj->where('name','LIKE',"%{$item}%")
                        ->where('client_slug',$client_slug)
                        ->orderBy('created_at','desc')
                        ->get(); 
            }elseif($tag){
                 $objs = $obj->where('settings','LIKE',"%{$tag}%")
                        ->where('client_slug',$client_slug)
                        ->orderBy('created_at','desc')
                        ->get(); 
            }
        
        
        // else{
        //        abort('404','cache file not found');
        //     //file_put_contents($filepath, json_encode($objs,JSON_PRETTY_PRINT));
        // }

        $ptags = Cache::get('ptags_'.$client_slug);
        if(!$ptags){
            $ptags = [];
            foreach($objs as $obj){
                $settings = json_decode($obj->settings);
                if(isset($settings->tags))
                {
                    $pieces = explode(',',$settings->tags);
                   
                    foreach($pieces as $k=>$p){
                        if(isset($ptags[$p]))
                            $ptags[$p]++;
                        else
                        $ptags[$p] = 1;
                        
                    }
                    
                }
                
            }
            Cache::forever('ptags_'.$client_slug,$ptags);
        }


        $view = $search ? 'public_list': 'public';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('obj',$obj)
                ->with('ptags',$ptags)
                ->with('app',$this);
    }

    public function upload(Request $request){

         if(isset($request->all()['file'])){
                
                $file      = $request->all()['file'];
                $fname = str_replace(' ','_',strtolower($file->getClientOriginalName()));
                $extension = strtolower($file->getClientOriginalExtension());

                if(!in_array($extension, ['csv'])){
                    $alert = 'Only CSV files are allowed';
                    return redirect()->route('product.upload')->with('alert',$alert);
                }

                 $file_path = Storage::disk('public')->putFileAs('excels', $request->file('file'),$fname,'public');
                 $fpath = Storage::disk('public')->path($file_path);

                 $row = 1;
                 
                if (($handle = fopen($fpath, "r")) !== FALSE) {
                  while (($data = fgetcsv($handle, 9000, ",")) !== FALSE) {
               
                    if($row==1){
                        $row++;
                        continue;
                    }
                    $row++;
                    $num = count($data);
                    $d=$data[0];
                    $p = Cache::remember('p_'.$data[0], 60, function() use($d) {
                        return Obj::where('slug',$d)->first();
                    });
                    $user = \auth::user()::where('email',$data[2])->where('client_slug',$data[1])->first();
                    $order = new Order();
                    $order->coupon($p->id,null,'ADMIN',$user);
                    
                   
                    
                  }
                  fclose($handle);
                }


                $alert = 'Data Records Added!';
                return redirect()->route('product.index')->with('alert',$alert);
            }
            else{
                return view('appl.'.$this->app.'.'.$this->module.'.upload')
                    ->with('stub','Create')
                    ->with('editor',true)
                    ->with('app',$this);

            }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $obj = new Obj();
        $this->authorize('create', $obj);

        $groups  = Group::where('status',1)->get();
        $tests  = Test::where('status',1)->get();
        $mocks  = Mock::where('status',1)->get();
        $clients = Client::get();

        return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Create')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('groups',$groups)
                ->with('tests',$tests)
                ->with('mocks',$mocks)
                ->with('clients',$clients)
                ->with('app',$this);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Obj $obj, Request $request)
    {
        try{
            
            // update slug with name if its empty
            if(!$request->get('slug')){
                $request->merge(['slug' => strtolower(str_replace(' ','-',$request->get('name')))]);
            }

            if($request->get('slug')){
                $exists = Obj::where('slug',$request->get('slug'))->first();
                if($exists)
                {
                  flash('Slug ('.$request->get('slug').') already in use. Kindly use a different slug.')->error();
                 return redirect()->back()->withInput(); 
                }
            }

            /* If image is given upload and store path */
            if(isset($request->all()['file'])){
                $file      = $request->all()['file'];
                $path = Storage::disk('public')->putFile('product', $request->file('file'));
                $request->merge(['image' => $path]);
            }

            if(!$request->get('price')){
                
                $request->merge(['price' => 0]);
            }


            /* create a new entry */
            $obj->create($request->except(['groups','file','tests']));

            $obj = Obj::where('slug',$request->get('slug'))->first();
            // attach the tags
            $groups = $request->get('groups');
            if($groups)
            foreach($groups as $group){
                $obj->groups()->attach($group);
            }

            // attach the tags
            $tests = $request->get('tests');
            if($tests)
            foreach($tests as $test){
                $obj->tests()->attach($test);
            }

            // attach the mocks
            $mocks = $request->get('mocks');
            if($mocks)
            foreach($mocks as $mock){
                $obj->mocks()->attach($mock);
            }

            /* update cache file of this product */
            $filename = $request->get('slug').'.json';
            $filepath = $this->cache_path.$filename;
            //file_put_contents($filepath, json_encode($obj,JSON_PRETTY_PRINT));

            /* update in cache folder main file */
            $filename = 'index.'.$this->app.'.'.$this->module.'.json';
            $filepath = $this->cache_path.$filename;
            $objs = $obj->orderBy('created_at','desc')
                        ->get(); 

             $filepath = $this->cache_path.$filename;

            Cache::forget($filepath);
            //file_put_contents($filepath, json_encode($objs,JSON_PRETTY_PRINT));

            flash('A new ('.$this->app.'/'.$this->module.') item is created!')->success();
            return redirect()->route($this->module.'.index');
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                flash('Some error in Creating the record')->error();
                 return redirect()->back()->withInput();;
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $obj = Obj::where('id',$id)->first();
        if(subdomain()!='prep' && $obj->client_slug!=subdomain()){
            abort(403,'Unauthorized Access');
        }
        $this->authorize('view', $obj);
        $settings = json_decode($obj->settings);
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.show')
                    ->with('obj',$obj)->with('app',$this)->with('settings',$settings);
        else
            abort(404);
    }


    /** 
     * PUBLIC LISTING
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($slug)
    {
        $filename = $slug.'.json';
        $filepath = $this->cache_path.$filename;


        if(request()->get('refresh')){
                Cache::forget($filepath);
        }
        
        $obj = Cache::get($filepath);

        if(request()->get('l1')){
            dd($obj);
        }

        if(!$obj){
            $obj = Obj::where('slug',$slug)->first();  
            if(!$obj)
                abort(404);
            
            
            $obj->groups = $obj->groups;
            foreach($obj->groups as $m=>$group){
                $obj->groups->tests = $group->tests;
                foreach($obj->groups->tests as $test){
                    $obj->groups->tests->testtype = $test->testtype;
                }
            }
            
            $test_ids = $obj->tests->pluck('id')->toArray();
            if(isset($obj->tests[0])){
                $test_ids_all = $obj->tests[0]->category->tests->pluck('id')->toArray();
                $t = array_diff_assoc($test_ids_all, $test_ids);
                $related_tests = Test::whereIn('id',$t)->limit(6)->get();
                $obj->related_tests = $related_tests;
            }else{
                $obj->related_tests = null;
            }

            Cache::forever($filepath,$obj);   
        }

        if(request()->get('dump'))
            dd($obj->tests->first());

        $mhash = ["xdasd","drwqa","fgdsf","gfdsg"];
        $this->mhash = $mhash[array_rand($mhash)];

        if(\auth::user())
            $obj->order = \auth::user()->orders()
                            ->where('product_id',$obj->id)
                            ->where('status',1)
                            ->orderBy('id','desc')->first();
        else
            $obj->order = null;

        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.view2')
                    ->with('obj',$obj)->with('app',$this);
        else
            abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $obj= Obj::where('id',$id)->first();
        $this->authorize('update', $obj);
        $groups  = Group::where('status',1)->get();
        $tests  = Test::where('status',1)->get();
        $mocks  = Mock::where('status',1)->get();
        $settings = json_decode($obj->settings);
        $clients = Client::get();

        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Update')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('groups',$groups)
                 ->with('tests',$tests)
                 ->with('mocks',$mocks)
                 ->with('clients',$clients)
                 ->with('settings',$settings)
                ->with('app',$this);
        else
            abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $obj = Obj::where('id',$id)->first();


            // change to uppercase
            if($request->get('name')){
                $request->merge(['name' => strtoupper($request->get('name'))]);
            }

             /* delete file request */
            if($request->get('deletefile')){

                if(Storage::disk('public')->exists($obj->image)){
                    Storage::disk('public')->delete($obj->image);
                }
                redirect()->route($this->module.'.show',[$id]);
            }

            $this->authorize('update', $obj);
            /* If file is given upload and store path */
            if(isset($request->all()['file'])){
                $file      = $request->all()['file'];
                $path = Storage::disk('public')->putFile('product', $request->file('file'));
                $request->merge(['image' => $path]);
            }

            
            // attach the tags
            $groups = $request->get('groups');
            if($groups){
                $obj->groups()->detach();
                foreach($groups as $group){
                $obj->groups()->attach($group);
                }
            }else{
            	$obj->groups()->detach();
            }


            //tags
            $settings = [];
            $settings['tags'] = $request->get('tags');
            $request->merge(['settings' => json_encode($settings)]);
            
            //refresh cache
            $filename = $obj->slug.'.json';
            $filepath = $this->cache_path.$filename;
            Cache::forget($filepath);

            

            Cache::forget('ptags');
            $tests = $request->get('tests');
            if($tests){
                $obj->tests()->detach();
                foreach($tests as $test){
                $obj->tests()->attach($test);
                }
            }else{
                $obj->tests()->detach();
            }

            $mocks = $request->get('mocks');
            if($mocks){
                $obj->mocks()->detach();
                foreach($mocks as $mock){
                $obj->mocks()->attach($mock);
                }
            }else{
                $obj->mocks()->detach();
            }

            $obj->update($request->except(['groups','file','tests','mocks'])); 


            /* update cache file of this product */
            $filename = $obj->slug.'.json';
            $filepath = $this->cache_path.$filename;

            $obj->tests = $obj->tests()->orderBy('name','asc')->get();

            $test_ids = $obj->tests->pluck('id')->toArray();
            if(isset($obj->tests[0])){
                $test_ids_all = $obj->tests[0]->category->tests->where('status',1)->pluck('id')->toArray();
                
                $t=array();
                    foreach($test_ids_all as $ts)
                        if(!in_array($ts,$test_ids))
                        array_push($t, $ts); 
                    
                shuffle($t);
                $t= array_slice($t,0,6);

                $related_tests = Test::whereIn('id',$t)->get();
                $obj->related_tests = $related_tests;
            }else{
                $obj->related_tests = null;
            }
            foreach($obj->tests as $test){
                $obj->tests->testtype = $test->testtype;
            }
                

            //file_put_contents($filepath, json_encode($obj,JSON_PRETTY_PRINT));

            /* update in cache folder main file */
            $filename = 'index.'.$this->app.'.'.$this->module.'.json';
            $filepath = $this->cache_path.$filename;
            $objs = $obj->orderBy('created_at','desc')
                        ->get(); 
           // file_put_contents($filepath, json_encode($objs,JSON_PRETTY_PRINT));
            
            $filepath = $this->cache_path.$filename;

            Cache::forget($filepath);

            flash('('.$this->app.'/'.$this->module.') item is updated!')->success();
            return redirect()->route($this->module.'.show',$id);
        }
        catch (QueryException $e){
           $error_code = $e->errorInfo[1];
            if($error_code == 1062){
                 flash('Some error in updating the record')->error();
                 return redirect()->back()->withInput();
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = Obj::where('id',$id)->first();
        $this->authorize('update', $obj);

         // remove file
        if(Storage::disk('public')->exists($obj->image))
            Storage::disk('public')->delete($obj->image);

        $obj->delete();

        flash('('.$this->app.'/'.$this->module.') item  Successfully deleted!')->success();
        return redirect()->route($this->module.'.index');
    }
}
