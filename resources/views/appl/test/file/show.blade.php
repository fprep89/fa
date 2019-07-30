@extends('layouts.app')
@section('content')

<nav aria-label="breadcrumb">
  <ol class="breadcrumb border bg-light">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin')}}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route($app->module.'.index') }}">{{ ucfirst($app->module) }}</a></li>
    <li class="breadcrumb-item">{{ $obj->response }}</li>
  </ol>
</nav>

@include('flash::message')

  <div class="row">

    <div class="col-md-12">
      <div class="card bg-light mb-3">
        <div class="card-body text-secondary">
          <p class="h2 mb-0"><i class="fa fa-file-o "></i> {{ $obj->test->name }} - {{ $obj->user->name }} 

            <a href="{{ route('review.notify',$obj->id)}}">
          <button class="btn btn-sm btn-outline-primary"><i class="fa fa-envelope"></i> Notify User</button>
          </a>
          @can('update',$obj)
            <span class="btn-group float-right" role="group" aria-label="Basic example">
              <a href="{{ route($app->module.'.edit',$obj->id) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
              <a href="{{ route($app->module.'.download',$obj->id) }}" class="btn btn-outline-secondary" data-tooltip="tooltip" data-placement="top" title="Download"><i class="fa fa-download"></i></a>
              <a href="#" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal" data-tooltip="tooltip" data-placement="top" title="Delete" ><i class="fa fa-trash"></i></a>
            </span>
            @endcan
          </p>
        </div>
      </div>

     
      <div class="card mb-4">
        <div class="card-body">
          
          @if($player)
          <div class="row mb-2">
            <div class="col-md-4"><b>File</b></div>
            <div class="col-md-8">
              <div class="border">
              <audio >
              <source id="player"  src="{{ url('/').'/uploads/'.$obj->response }}" type="audio/mp3">
              </audio>
            </div>
            </div>
          </div>
          @else
          <div class="row mb-2">
            <div class="col-md-4"><b>File</b></div>
            <div class="col-md-8">{{ $obj->response }}</div>
          </div>
          @endif
          <div class="row mb-2">
            <div class="col-md-4"><b>Expert Feedback</b></div>
            <div class="col-md-8">
              @if($obj->answer)
              <div class="p-3 bg-light border">{!! $obj->answer !!}</div>
              @else
                - NA -
              @endif
            </div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Uploaded at</b></div>
            <div class="col-md-8">{{ ($obj->created_at) ? $obj->created_at->diffForHumans() : '' }}</div>
          </div>
          <div class="row mb-2">
            <div class="col-md-4"><b>Reviewed at</b></div>
            <div class="col-md-8">{{ ($obj->updated_at) ? $obj->updated_at->diffForHumans() : '' }}</div>
          </div>
        </div>
      </div>

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