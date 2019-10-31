@extends('layouts.front')
@section('title', 'Tests | First Academy')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')

<div class="" style="background:#71bce2;"> 
<div class="container ">
    <div class="row p-3 p-md-0">
        <div class="col-12 col-md-8">
            <div class="p-3 p-md-3"></div>
    <div class="heading  " style="color:#fff;font-family: 'Chivo', sans-serif;font-weight: 900;line-height: 1.2">
    Start your <br>Preparation now !
    </div>
    <div class="heading2  mb-4 mt-3" style="color:#b7e6ff">
    Practice, Analyze and Improve.
    </div>

    @guest
    <a href="{{ route('register')}}">
    <button class="btn  btn-light  btn-lg p-2 pr-4 pl-4" style="color: #71bce2;font-weight: 900;"><b>Get Started for Free</b></button>
    </a>
    
    @else
    <a href="{{ route('home')}}">
    <button class="btn btn-success btn-orange btn-lg">Open Dashboard</button>
    </a>
    <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                    <button class="btn btn-warning btn-lg ">Logout</button>
                    </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
    @endguest
   <div class="p-4"></div>
     </div>
        <div class="col-12 col-md-4">
             <img class="mb-2 mt-2 d-none d-md-block w-100 p-4" src="{{ asset('images/general/front5.png') }}" alt=""  >
        </div>
    </div>
</div>
</div>

<div class="p-2 pt-md-5 pb-md-4 bg-white">
  @include('blocks.popular_ielts')
</div>
<div class="p-2 p-md-5 pb-md-3" style="background: #f3fbff">
  @include('blocks.free_listening_tests')
</div>
<div class="p-2 p-md-5 pb-md-3">
  @include('blocks.free_reading_tests')
</div>
<div class="p-2 p-md-5 pb-md-3" style="background: #f3fbff">
  @include('blocks.experience_best')
</div>


@endsection