@if(count($objs)!=0)
@foreach($objs as $obj)
@if($obj->status)
<div class="col-12 col-md-6 col-lg-4 mb-4">
    <div class="card" >
                @if(\Storage::disk('public')->exists($obj->image)  && $obj->image)
                   <div class="card-img-top bg-image" style="background-image: url({{ asset(url('/').'/storage/'.$obj->image)}})"> 
</div>
  @endif
  <div class="card-body">
    <h5 class="card-title">{{ $obj->name}} 
       </h5>
       @if(!$obj->price)
       <span class="badge badge-warning">FREE</span>
       @else
       <span class="badge badge-primary">PREMIUM</span>
       @endif
    <p class="card-text">
      {{ substr(strip_tags($obj->details),0,200) }}
      @if(strlen(strip_tags($obj->details))>200 )
      ...
      @endif
    </p>

    <a href="{{ route('test',$obj->slug)}}" class="btn btn-outline-secondary mb-1">view</a>

  </div>
</div>
</div>
@endif
@endforeach
@else
<div class="container">
<div class="card card-body bg-light">
  No test found
</div>
</div>
@endif