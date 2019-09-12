@extends('layouts.app')
@section('title', $obj->name.' | Test | First Academy')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb border bg-light">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route($app->module.'.index') }}">{{ ucfirst($app->module) }}</a></li>
    <li class="breadcrumb-item">{{ $obj->name }}</li>
  </ol>
</nav>

@include('flash::message')

  <div class="row">

    <div class="col-12 col-md-9">
      <div class="card bg-light mb-3">
        <div class="card-body text-secondary">
          <p class="h2 mb-0"><i class="fa fa-th "></i> {{ $obj->name }} 
            
          @can('update',$obj)
            <span class="btn-group float-right" role="group" aria-label="Basic example">
              <a href="{{ route($app->module.'.edit',$obj->id) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
              <a href="{{ route('test',$obj->slug) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="public"><i class="fa fa-globe"></i></a>
              <a href="{{ route($app->module.'.view',$obj->slug) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="view"><i class="fa fa-eye"></i></a>
              <a href="#" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal" data-tooltip="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash"></i></a>
            </span>
            @endcan
          </p>
        </div>
      </div>

     
      <div class="card mb-4">
        <div class="card-body">
          <div class="row mb-2">
            <div class="col-md-4"><b>Name</b></div>
            <div class="col-md-8">{{ $obj->name }}</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Slug</b></div>
            <div class="col-md-8">{{ $obj->slug }}</div>
          </div>
    
          <div class="row mb-2">
            <div class="col-md-4"><b>Category</b></div>
            <div class="col-md-8">
              <a href="{{ route('category.show',$obj->category->id) }}">
                {{ $obj->category->name }}
              </a>
            </div>
          </div>

          @if($obj->testtype)
          <div class="row mb-2">
            <div class="col-md-4"><b>Type</b></div>
            <div class="col-md-8">
              <a href="{{ route('type.show',$obj->testtype->id) }}">
                {{ $obj->testtype->name }}
              </a>
            </div>
          </div>
          @endif

          @if($obj->group)
          <div class="row mb-2">
            <div class="col-md-4"><b>Group</b></div>
            <div class="col-md-8">
              <a href="{{ route('group.show',$obj->group->id) }}">
                {{ $obj->group->name }}
              </a>
            </div>
          </div>
          @endif

          <div class="row mb-2">
            <div class="col-md-4"><b>Details</b></div>
            <div class="col-md-8">{!! $obj->details !!}</div>
          </div>

           <div class="row mb-2">
            <div class="col-md-4"><b>Instructions</b></div>
            <div class="col-md-8">{!! $obj->instructions !!}</div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Description</b></div>
            <div class="col-md-8">{!! $obj->description !!}</div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Audio File</b></div>
            <div class="col-md-8">
              @if(\Storage::disk('public')->exists($obj->file) && $obj->file )
              <div class="bg-light border mb-3">
                 <audio>
                  <source src="{{ asset(\storage::disk('public')->url($obj->file))}}" type="audio/mp3">
                  </audio>
              </div>
              <form method="post" action="{{route($app->module.'.update',[$obj->id])}}" >
                 <input type="hidden" name="_method" value="PUT">
                 <input type="hidden" name="deletefile" value="1">
                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-sm btn-outline-danger">Delete File</button>
              </form>
              @else
               <span class="text-muted"><i class="fa fa-exclamation-triangle"></i> audio file path not found </span>
              @endif
            </div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Image</b></div>
            <div class="col-md-8">
              @if(\Storage::disk('public')->exists($obj->image) && $obj->image )
              <div class="bg-light border mb-3">
                 <img src="{{ asset(\storage::disk('public')->url($obj->image))}}" class="w-100"/>
              </div>
              <form method="post" action="{{route($app->module.'.update',[$obj->id])}}" >
                 <input type="hidden" name="_method" value="PUT">
                 <input type="hidden" name="deleteimage" value="1">
                 <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-sm btn-outline-danger">Delete Image</button>
              </form>
              @else
               <span class="text-muted"><i class="fa fa-exclamation-triangle"></i> image  path not found </span>
              @endif
            </div>
          </div>

          
          <div class="row mb-2">
            <div class="col-md-4"><b>Marks</b></div>
            <div class="col-md-8">{{ $obj->marks }}</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Test Time</b></div>
            <div class="col-md-8">{{ $obj->test_time }} min</div>
          </div>

          <div class="row mb-2">
            <div class="col-md-4"><b>Price</b></div>
            <div class="col-md-8">
              @if($obj->price===0)
                <span class="text-secondary">FREE</span>
              @elseif($obj->price)
                <i class="fa fa-rupee"></i> {{ $obj->price }} 
              @else
               -
              @endif
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Validity</b></div>
            <div class="col-md-8">{{ $obj->validity }} months</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Status</b></div>
            <div class="col-md-8">@if($obj->status==0)
                    <span class="badge badge-warning">Inactive</span>
                  @elseif($obj->status==1)
                    <span class="badge badge-success">Active</span>
                  @endif</div>
          </div>
          
          <div class="row mb-2">
            <div class="col-md-4"><b>Created </b></div>
            <div class="col-md-8">{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</div>
          </div>
        </div>
      </div>

      <div class="bg-light p-4 rounded mb-4">
        <h2>Test Cache</h2>
        
        @if(file_exists('../storage/app/cache/test/test.'.$obj->slug.'.json'))
        <p> Awesome ! your test is cached.<br>
        <small>Updated:  {{\Carbon\Carbon::parse($obj->cache_updated_at)->diffForHumans() }}</small></p>
        <a href="{{ route('test.cache',$obj->id)}}">
          <button class="btn btn-outline-success">Update Cache</button>
        </a>
        <a href="{{ route('test.cache.delete',$obj->id)}}">
          <button class="btn btn-outline-danger">Delete Cache</button>
        </a>
        @else
        <p>Cache can speedup performance by 20% so create one now.</p>
        <a href="{{ route('test.cache',$obj->id)}}">
          <button class="btn btn-outline-primary">Create Cache</button>
        </a>
        @endif
        
      </div> 


    </div>

    <div class="col-12 col-md-3">
      @include('appl.test.snippets.menu')
    </div>

     

  </div> 


  <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Deletion</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        This following action is permanent and it cannot be reverted.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
        <form method="post" action="{{route($app->module.'.destroy',$obj->id)}}">
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        	<button type="submit" class="btn btn-danger">Delete Permanently</button>
        </form>
      </div>
    </div>
  </div>
</div>


@endsection