@extends('layouts.app')
@include('meta.createedit')
@section('content')

@include('flash::message')
  <div class="card">
    <div class="card-body">

      @if($stub=='Create')
      <form method="post" action="{{route($app->module.'.store')}}" enctype="multipart/form-data">
      @else
      <form method="post" action="{{route($app->module.'.update',$obj->id)}}" enctype="multipart/form-data">
      @endif 
      <h1 class="p-3 border bg-light mb-3">
        @if($stub=='Create')
          Create {{ $app->module }}
        @else
          Update {{ $app->module }}
        @endif  

        <button type="submit" class="btn btn-outline-success float-right">Save</button>
       </h1>
      
      

      <div class="row">
        <div class="col-12 col-md-6">

          <div class="form-group">
            <label for="formGroupExampleInput ">ID Number</label>
            <input type="text" class="form-control" name="idno" id="formGroupExampleInput" placeholder="Enter the id number (optional)" 
                @if($stub=='Create')
                value="{{ (old('idno')) ? old('idno') : '' }}"
                @else
                value = "{{ $obj->idno }}"
                @endif
              >
          </div>

          <div class="form-group">
            <label for="formGroupExampleInput ">Email</label>
            <input type="text" class="form-control" name="email" id="formGroupExampleInput" placeholder="Enter the Email" 
                @if($stub=='Create')
                value="{{ (old('email')) ? old('email') : request()->get('email') }}"
                @else
                value = "{{ $obj->email }}"
                @endif
              >
          </div>

           @if(\auth::user()->admin==1)
           <div class="form-group">
              <label for="formGroupExampleInput ">Role</label>
              <select class="form-control" name="admin">
                
                <option value="0" @if(isset($obj)) @if($obj->admin===0) selected @endif @endif >User</option>
                <option value="1" @if(isset($obj)) @if($obj->admin==1) selected @endif  @endif >Administrator</option>
                <option value="2" @if(isset($obj)) @if($obj->admin===2) selected @endif @endif >Employee</option>
                <option value="4" @if(isset($obj)) @if($obj->admin===4) selected @endif @endif >Faculty</option>
                <option value="3" @if(isset($obj)) @if($obj->admin===3) selected @endif @endif >Tele Caller</option>
                <option value="5" @if(isset($obj)) @if($obj->admin===5) selected @endif @endif >Client</option>
              </select>
            </div>
           @endif


        </div>
        <div class="col-12 col-md-6">
          <div class="form-group">
            <label for="formGroupExampleInput "> Name</label>
            <input type="text" class="form-control" name="name" id="formGroupExampleInput" placeholder="Enter the Name" 
                @if($stub=='Create')
                value="{{ (old('name')) ? old('name') : request()->get('name') }}"
                @else
                value = "{{ $obj->name }}"
                @endif
              >
          </div>

          <div class="form-group">
            <label for="formGroupExampleInput ">Phone</label>
            <input type="text" class="form-control" name="phone" id="formGroupExampleInput" placeholder="Enter the phone number" 
                @if($stub=='Create')
                value="{{ (old('phone')) ? old('phone') : request()->get('phone') }}"
                @else
                value = "{{ $obj->phone }}"
                @endif
              >
          </div>

          <div class="form-group">
            <label for="formGroupExampleInput ">Status</label>
            <select class="form-control" name="status">
              <option value="1" @if(isset($obj)) @if($obj->status==1) selected @else selected @endif  @endif >Active</option>
              <option value="0" @if(isset($obj)) @if($obj->status===0) selected @endif @endif >Blocked</option>
            </select>
          </div>

        </div>
      </div>
      

      
   

    <div class="form-group">
        <label for="formGroupExampleInput">Products</label>
         <div class=" card p-3">
          <div class="row">
          @foreach($products as $product)
          @if($product->status==1)
          <div class="col-12 col-md-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="products[]" value="{{$product->id}}" id="defaultCheck1" @if($obj->hasProduct($product->id))) checked @endif >
            <label class="form-check-label" for="defaultCheck1">
              {{ strip_tags($product->name) }} 
            </label>
          </div>
          </div>
          @endif
          @endforeach
         </div>
         </div>
      </div>
      
       
       

      
    


     <div class="form-group">
        <label for="formGroupExampleInput">Tests</label>
         <div class=" card p-3">
          <div class="row">
          @foreach($tests as $test)
          @if($test->status==1)
          <div class="col-12 col-md-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="tests[]" value="{{$test->id}}" id="defaultCheck1" @if($obj->hasTest($test->id))) checked @endif>
            <label class="form-check-label" for="defaultCheck1">
              {{ strip_tags($test->name) }}
            </label>
          </div>
          </div>
          @endif
          @endforeach
         </div>
         </div>
      </div>

      
      
      

     

      

      @if($stub=='Update')
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="id" value="{{ $obj->id }}">
      @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
       <button type="submit" class="btn btn-success">Save</button>
    </form>
    </div>
  </div>
@endsection