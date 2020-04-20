@extends('layouts.app')
@include('meta.createedit')
@section('content')

@if($stub=='Create')
      <form method="post" action="{{route($app->module.'.store',$app->test->id)}}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{route($app->module.'.update',[$app->test->id,$obj->id])}}" enctype="multipart/form-data">
      @endif  
@include('flash::message')
  <div class="card mb-3">
    <div class="card-body">
      <h1 class="p-3 border bg-light mb-3">
        @if($stub=='Create')
          Create {{ $app->module }}
        @else
          Update {{ $app->module }}
        @endif  

        <button type="submit" class="btn btn-primary float-right btn-lg">Save</button>
       </h1>
      
      
      

      
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group">
        <label for="formGroupExampleInput ">Label</label>
        <input type="text" class="form-control" name="label" id="formGroupExampleInput" placeholder="Enter the label" 
            @if($stub=='Create')
            value="{{ (old('label')) ? old('label') :'' }}"
            @else
            value = "{{ $obj->label }}"
            @endif
          >
        
        </div>

        <div class="row">
          <div class="col-12 col-md-4">
            <div class="form-group">
            <label for="formGroupExampleInput ">Prefix</label>
            <textarea class="form-control " name="prefix"  rows="3">@if($stub=='Create'){{ (old('prefix')) ? old('prefix') : '' }} @else {{ $obj->prefix }} @endif
            </textarea>
            </div>
          </div>

        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="formGroupExampleInput ">Answer</label>
            <textarea class="form-control " name="answer"  rows="3">@if($stub=='Create'){{ (old('answer')) ? old('answer') : '' }} @else {{ $obj->answer }} @endif
            </textarea>
          </div>
        </div>

        <div class="col-12 col-md-4">
          <div class="form-group">
            <label for="formGroupExampleInput ">Suffix</label>
            <textarea class="form-control " name="suffix"  rows="3">@if($stub=='Create'){{ (old('suffix')) ? old('suffix') : '' }} @else {{ $obj->suffix }} @endif
            </textarea>
          </div>
        </div>


        </div>
        
        

        </div>

        <div class="col-12 col-md-6">
          <div class="form-group">
            <img src="{{ asset('images/tests/fillup.jpg')}}" class="w-100">
            <small class="p-4">Note: The above image is for default layout. <a href="{{ route('fillup')}}">check other layouts</a></small>
          </div>

        </div>
        
      </div> 

</div>
</div>

<div class="card">
  <div class="card-header">
    <h3 class="mb-0">Details</h3>
  </div>
  <div class="card-body">
      <div class="row">
        <div class="col-12 col-md-3">
          <div class="form-group">
        <label for="formGroupExampleInput ">Sno</label>
        <input type="text" class="form-control" name="sno" id="formGroupExampleInput" 
            @if($stub=='Create')
            value="{{ (old('sno')) ? old('sno') : $app->sno }}"
            @else
            value = "{{ $obj->sno }}"
            @endif
          >
          <small class="text-secondary"> Used for sequencing the fillup elements in ielts and oet tests. </small>
      </div>

        </div>
        <div class="col-12 col-md-3">
          <div class="form-group">
        <label for="formGroupExampleInput ">Qno  <span data-toggle="tooltip" title="In IELTS test to make question as 'example question' enter -1 in Qno" class="text-secondary"><i class="fa fa-info-circle" aria-hidden="true"></i></span></label>
        <input type="text" class="form-control" name="qno" id="formGroupExampleInput" placeholder="Enter the Question number" 
            @if($stub=='Create')
            value="{{ (old('qno')) ? old('qno') : $app->qno }}"
            @else
            value = "{{ $obj->qno }}"
            @endif
          >
          <small class="text-secondary"> Used as prefix to the blank in the question. Also used for sequencing the elements in grammar  tests.  </small>
      </div>
          
        </div>

        <div class="col-12 col-md-2">
         <div class="form-group">
        <label for="formGroupExampleInput ">Layout </label>
        <select class="form-control" name="layout">
          <option value="" @if(isset($obj)) @if(!$obj->layout) selected @endif @endif > Default </option>
          <option value="ielts_two_blank" @if(isset($obj)) @if($obj->layout=='ielts_two_blank') selected @endif @endif >Two Blanks - format 1</option>
          <option value="two_blank" @if(isset($obj)) @if($obj->layout=='two_blank') selected @endif @endif >Two Blanks - format 2</option>
          <option value="paragraph" @if(isset($obj)) @if($obj->layout=='paragraph') selected @endif @endif >Paragraph</option>

          <option value="cloze_test" @if(isset($obj)) @if($obj->layout=='cloze_test') selected @endif @endif >Dropdown - Format 1</option>
          <option value="dropdown" @if(isset($obj)) @if($obj->layout=='dropdown') selected @endif @endif >Dropdown - Format 2</option>

          <option value="duolingo_missing_letter" @if(isset($obj)) @if($obj->layout=='duolingo_missing_letter') selected @endif @endif >Missing Letters</option>

          @if($app->test->category=='GRE')
          <option value="gre_sentence" @if(isset($obj)) @if($obj->layout=='gre_sentence') selected @endif @endif >Gre Sentence</option>
          @endif

          @if($app->test->category=='PTE')
          <option value="dropin" @if(isset($obj)) @if($obj->layout=='dropin') selected @endif @endif >PTE Drop In</option>
          <option value="pte_reorder" @if(isset($obj)) @if($obj->layout=='pte_reorder') selected @endif @endif >PTE Reorder</option>
          @endif
        </select>
        <small class="text-secondary"> Layout is the template design on how the question should look in the user view.  <br><a href=""><i class="fa fa-link"></i> help images</a></small>
      </div>

        </div>

        


        @if(!in_array(strtolower($app->test->testtype->name),['grammar']))
        <div class="col-12 col-md-2">
       <div class="form-group">
        <label for="formGroupExampleInput ">Section</label>
        <select class="form-control" name="section_id">
          <option value="" @if(isset($obj)) @if(!$obj->section_id) selected @endif @endif >-None-</option>
          @foreach($sections as $section)
          <option value="{{$section->id}}" @if(isset($obj)) @if($obj->section_id == $section->id) selected  @endif @endif  >{{ $section->name }}</option>
          @endforeach
        </select>
        <small class="text-secondary"> Connect the question to the section block </small>
      </div>
    </div>
      @endif

      <div class="col-12 col-md">
        <div class="form-group">
        <label for="formGroupExampleInput ">
          @if(in_array(strtolower($app->test->testtype->name),['listening','reading']))
  Extract
  @else
  Passage
  @endif
        </label>
        <select class="form-control" name="extract_id">
          <option value="" @if(isset($obj)) @if(!$obj->extract_id) selected @endif @endif >- None -</option>
          @foreach($extracts as $extract)
          <option value="{{$extract->id}}" @if(isset($obj)) @if($obj->extract_id == $extract->id) selected  @endif  @endif >{{ $extract->name }}</option>
          @endforeach
        </select>
        <small class="text-secondary"> Mentioned passage will be prefixed to the question. </small>
      </div>
      </div>

      </div> 
      

      <hr>
      
      

      
      

      <div class="form-group">
        <label for="formGroupExampleInput">Tags</label>
        <small class="text-secondary"> Experimental feature for future use. We might use this to tag the questions to different keywords. (you can ignore this for timebeing)</small>
        <div class="row">
          @foreach($tags->groupBy('name') as $name=>$t)
          <div class="col-12 col-md-6">
          <div class="border rounded p-2 mb-1 bg-light">
          <div class="row">
          <div class="col-12 col-md-2"><b>{{$name}}</b></div>
          @foreach($t as $tag)
          <div class="col-12 col-md-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tags[]" value="{{$tag->id}}" id="defaultCheck1" @if($obj->tags->contains($tag->id))) checked @endif>
            <label class="form-check-label" for="defaultCheck1">
               {{ $tag->value }}
            </label>
          </div>
          </div>
          @endforeach
        </div>
          </div>
        </div>
          @endforeach
        </div>
      </div>
      
   

      @if($stub=='Update')
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{ $obj->id }}">
      @else
      <input type="hidden" name="sno" value="{{ $app->sno }}">  
      @endif
      
      <input type="hidden" name="test_id" value="{{ $app->test->id }}">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      
    
    </div>
  </div>
  </form>
@endsection