@foreach($obj->tests as $k=>$test)
@if($test->status)
<div class="col-12 
@if(count($obj->tests)>1) col-md-12  @endif
mb-3 test_block" style="@if($k>2)display:none;@endif">
<div class="card" style="box-shadow: 2px 3px #f8f9fa;background-image: linear-gradient(#fbf0f2 5%, white 80%,white 15%); " >

  <div class="card-body">
    @if($test->status)
    @if(\auth::user())
    @if(!\auth::user()->attempt($test->id))
    <a href="{{ route('test.instructions',$test->slug)}}?product={{$obj->slug}}" class="btn btn-primary mb-1 float-right"><i class="fa fa-paper-plane"></i> Take Test</a>
    @else
    @if($test->testtype->name == 'SPEAKING' || $test->testtype->name == 'WRITING')
    <a href="{{ route('test.try',$test->slug)}}?product={{$obj->slug}}" class="btn btn-secondary mb-1 float-right"><i class="fa fa-eye"></i> View Response</a>
    @else
    <a href="{{ route('test.analysis',$test->slug)}}?product={{$obj->slug}}" class="btn btn-secondary mb-1 float-right"><i class="fa fa-bar-chart"></i> Test Report</a>
    @endif
    @endif
    @else
    <a href="{{ route('test.instructions',$test->slug)}}?product={{$obj->slug}}" class="btn btn-primary mb-1 float-right"><i class="fa fa-paper-plane"></i> Take Test</a>
    @endif
    @endif

    <h4 class="card-title"><i class="fa fa-clone"></i> {{ $test->name}} 

      @if($test->price==0)
      <span class="badge badge-warning">FREE</span>
      @endif
      @can('update',$obj)
      <a href="{{ route('test.edit',$test->id) }}" class="h5" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
    @endcan</h4>

    @if($test->marks)
    <div class="">{{$test->marks}} Questions 
      @if($test->test_time)
      | {{$test->test_time}} min
      @endif
    </div>
    @elseif($test->test_time)
    <div class="">{{$test->test_time}} min</div>
    @else
    @endif

    @if($test->level)
    <div class="">
      <B>Level : </B>

      <span class="text-success">
        @for($i=$test->level;$i>0;$i--)
        <i class="fa fa-circle "></i>
        @endfor
      </span>
      <span class="text-secondary">
        @for($i=(5-$test->level);$i>0;$i--)
        <i class="fa fa-circle-o "></i>
        @endfor
      </span>
    </div>
    @endif

  </div>
</div>
</div>
@endif
@endforeach