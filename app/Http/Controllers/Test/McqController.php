<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Test\Mcq as Obj;
use App\Models\Test\Extract;
use App\Models\Test\Test;
use App\Models\Test\Tag;

class McqController extends Controller
{
    /*
        The MCQ Controller
    */

    public function __construct(){
        $this->app      =   'test';
        $this->module   =   'mcq';
        if(request()->route('test')){
            $this->test = Test::find(request()->route('test'));
        } 
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
        
        $objs = $obj->where('test_id',$this->test->id)->where(function ($query) use ($item) {
                $query->where('question','LIKE',"%{$item}%")
                    ->orWhere('a','LIKE',"%{$item}%")
                     ->orWhere('b','LIKE',"%{$item}%")
                    ->orWhere('c','LIKE',"%{$item}%");
                    })
                    ->orderBy('extract_id','asc')
                    ->orderBy('qno','asc')
                    ->paginate(config('global.no_of_records'));   
        $view = $search ? 'list': 'index';

        return view('appl.'.$this->app.'.'.$this->module.'.'.$view)
                ->with('objs',$objs)
                ->with('obj',$obj)
                ->with('app',$this);
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

        /* add the serial number */
        if(Obj::orderBy('id','desc')->where('test_id',$this->test->id)->first())
            $this->sno = Obj::orderBy('id','desc')->where('test_id',$this->test->id)->first()->sno+1;
        else
            $this->sno = 1;

        $extracts = Extract::where('test_id',$this->test->id)->get();
         $tags = Tag::all();
        return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Create')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('extracts',$extracts)
                ->with('tags',$tags)
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
            $user = \auth::user();

            $request->session()->put('extract_id', $request->get('extract_id'));
            /* upload images if any */
            $question = summernote_imageupload($user,$request->get('question'));
            $a = summernote_imageupload($user,$request->get('a'));
            $b = summernote_imageupload($user,$request->get('b'));
            $c= summernote_imageupload($user,$request->get('c'));
            $d= summernote_imageupload($user,$request->get('d'));
            
            /* merge the updated data in request */
            $request->merge(['question' => Strip_tags($question),'a' => Strip_tags($a),'b' => Strip_tags($b),'c' => Strip_tags($c),'d' => Strip_tags($d)]);
            
            /* create a new entry */
            $obj = $obj->create($request->except(['tags']));

            // attach the tags
            $tags = $request->get('tags');
            if($tags)
            foreach($tags as $tag){
                $obj->tags()->attach($tag);
            }

            flash('A new ('.$this->app.'/'.$this->module.') item is created!')->success();
            return redirect()->route($this->module.'.index',[$this->test->id]);
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
    public function show($test_id,$id)
    {
        $obj = Obj::where('id',$id)->first();

        $this->authorize('view', $obj);
        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.show')
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
    public function edit($test_id,$id)
    {
        $obj= Obj::where('id',$id)->first();
        $this->authorize('update', $obj);

        $extracts = Extract::where('test_id',$this->test->id)->get();
         $tags = Tag::all();

        if($obj)
            return view('appl.'.$this->app.'.'.$this->module.'.createedit')
                ->with('stub','Update')
                ->with('obj',$obj)
                ->with('editor',true)
                ->with('extracts',$extracts)
                ->with('tags',$tags)
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
    public function update(Request $request, $test_id,$id)
    {
        try{
            $obj = Obj::where('id',$id)->first();

            $this->authorize('update', $obj);

            $user = \auth::user();
            $request->session()->put('extract_id', $request->get('extract_id'));
            /* upload images if any */
            $question = summernote_imageupload($user,$request->get('question'));
            $a = summernote_imageupload($user,$request->get('a'));
            $b = summernote_imageupload($user,$request->get('b'));
            $c= summernote_imageupload($user,$request->get('c'));
            $d= summernote_imageupload($user,$request->get('d'));
            
            /* merge the updated data in request */
            $request->merge(['question' => Strip_tags($question),'a' => Strip_tags($a),'b' => Strip_tags($b),'c' => Strip_tags($c),'d' => Strip_tags($d)]);

            $tags = $request->get('tags');
            if($tags){
                $obj->tags()->detach();
                foreach($tags as $tag){
                $obj->tags()->attach($tag);
                }
            }else{
                $obj->tags()->detach();
            }

            $obj = $obj->update($request->all()); 
            flash('('.$this->app.'/'.$this->module.') item is updated!')->success();
            return redirect()->route($this->module.'.show',[$test_id,$id]);
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
    public function destroy($test_id,$id)
    {
        $obj = Obj::where('id',$id)->first();
        $this->authorize('update', $obj);
        
        /* remove images before deleting */
        summernote_imageremove($obj->question);
        summernote_imageremove($obj->a);
        summernote_imageremove($obj->b);
        summernote_imageremove($obj->c);
        summernote_imageremove($obj->d);

        $obj->delete();

        flash('('.$this->app.'/'.$this->module.') item  Successfully deleted!')->success();
        return redirect()->route($this->module.'.index',$this->test->id);
    }
}
