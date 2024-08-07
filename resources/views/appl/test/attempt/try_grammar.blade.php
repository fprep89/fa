@extends('layouts.clean')
@section('title', 'Grammar Test - '.$test->name)
@section('description', 'The Test page of '.$test->name)
@section('keywords', 'practice tests, '.$test->name)
@section('content')

@guest
@if(($test->status!=2 && $test->status!=3) && !request()->get('source'))
<div class="alert alert-warning alert-dismissible alert-important fade show" role="alert">
  <strong>Note:</strong> Only registered users can submit the test and view the result. 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
@endguest

<div class="container" style="padding-left:0px;padding-right:0px;">
    <form id="test" class="test" action="{{route('attempt.store',$app->test->slug)}}" method="post" id="write">  

    <div class="row p-0 m-0">
        <div class="col-12 col-md-8 col-lg-8">
            
            <div class="mb-3">
                <div class="part">
                <h3><i class="fa fa-clone"></i> {{ $test->name}}</h3>
                @if(strip_tags($test->description))
                <p>{!! $test->description !!}</p>
                @endif
                </div>
                <div class="bg-white border-top p-4">
                @if(count($test->mcq_order(1))!=0)
                    @include('appl.test.attempt.blocks.mcq_grammar')
                @endif

                @if(count($test->fillup_order(1))!=0)
                    @include('appl.test.attempt.blocks.fillup_grammar')
                @endif
                </div>
            </div>
            <div class="p-5 d-block d-md-none"></div>

        </div>
        <div class="col-12 col-md-4 col-lg-4">
            @if(isset($view))
            <input type="hidden" name="admin" value="1">
            @endif
            <input type="hidden" name="test_id" value="{{ $app->test->id }}">
            <input type="hidden" name="user_id" value="@if(\auth::user())
            {{ \auth::user()->id }}
            @endif
            ">
            <input type="hidden" name="product" value="@if($product){{ $product->slug }} @endif">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @include('appl.test.attempt.blocks.qno')
        </div>
        
    </div>
    @include('appl.test.attempt.blocks.modal')
    </form>
</div>

@endsection
