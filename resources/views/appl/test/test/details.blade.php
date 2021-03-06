@extends('layouts.meta')
@section('title', $test->name.' - '.getenv('APP_NAME'))
@section('description', $test->details)
@section('keywords', $test->name)
@if(\Storage::disk('public')->exists('/storage/test/'.$test->slug.'_300.jpg'))
  @section('image', asset('/storage/test/'.$test->slug.'_300.jpg')) 
@endif

@section('content')

@include('flash::message')
<div  class="row ">
  <div class="col-md-12">
   <div class="card  mb-0 mb-lg-4" >
    <div class="card-body p-4 p-md-5">
      <div class="row">
        <div class="col @if($obj->image) col-md-9 col-lg-10 @endif">
         <h1 class="h1 mb-0"> {{ $obj->name }} 
          @can('update',$obj)
          <a href="{{ route('test.edit',$obj->id) }}" class="h5" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
          @endcan
        </h1>
        <p>
          {!! $obj->details !!} 
        </p>
      @if($obj->status==2)
        @include('appl.test.test.blocks.open')
      @else
        @include('appl.test.test.blocks.access')
      @endif
      </div>
      @if($obj->image)
      <div class="col-12  col-md-3 col-lg-2">
        @include('appl.test.test.blocks.image')
      </div>
      @endif
    </div>
  </div>
</div>
</div>
</div>
@endsection


