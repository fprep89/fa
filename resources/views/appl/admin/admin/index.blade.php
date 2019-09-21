@extends('layouts.app')
@section('title', 'Admin | First Academy')
@section('description', 'Take a free IELTS | OET test completely free. Full-length OET practice test for free! Free IELTS writing band scores. Test your vocabulary for OET and IELTS.')
@section('keywords', 'IELTS Practice Test, OET Practice Online, OET Online Training, Vocabulary for IELTS, Vocabulary for OET')
@section('content')
<div class="container">
    <div class="row">
        @if(\auth::user()->admin!=4)
        <div class="col-12 col-md-4 col-lg-4">
            <div class="bg-primary text-light  rounded p-4 mb-4">
                <h3><i class="fa fa-user"></i> Users <Span class="float-right">{{$data['users']->count()}}</Span></h3>
                <hr>
                @foreach($data['users'] as $k=>$user)
                <div class="mb-2"><a href="{{ route('user.show',$user->id) }}" class="text-white">{{$user->name}} </a><span class="float-right text-info">{{ $user->created_at->diffForHumans()}}</span></div>
                @if($k==4)
                    @break
                @endif
                @endforeach
                

                <a href="{{ route('user.index')}}"><button class="btn btn-outline-light btn-sm mt-3">view all</button></a>
            </div>

            <div class="bg-secondary text-light rounded p-4 mb-4">
                <h3 class="mb-0"><i class="fa fa-tag"></i> FA5Y9 <Span class="float-right ">{{ $data['coupon']->count() }}</Span></h3>

                <a href="{{ route('order.index')}}?coupon=FA5Y9"><button class="btn btn-outline-light btn-sm mt-3">view list</button></a>
                
            </div>
        </div>
        @endif
        <div class="col-12 col-md-8 col-lg-8">
                <div class="row ">
        @if(\auth::user()->admin==1)
        <div class="col-6 col-md-3 col-lg-3">
            <a href="{{ route('category.index') }}">
            <div class="border bg-light p-4 rounded mb-4">
                <div>
                    <img src="{{ asset('images/admin/category.png') }}" class="w-100 mb-3" >
                    <div class="text-center">Test Categories</div>
                </div>
            </div>
            </a>
        </div>

        <div class="col-6 col-md-3 col-lg-3">
            <a href="{{ route('type.index') }}">
            <div class="border bg-light p-4 rounded mb-4">
                <div>
                    <img src="{{ asset('images/admin/type.png') }}" class="w-100 mb-3" >
                    <div class="text-center">Test Types</div>
                </div>
            </div>
            </a>
        </div>

        
        <div class="col-6 col-md-3 col-lg-3">
            <a href="{{ route('tag.index') }}">
            <div class="border bg-light p-4 rounded mb-4">
                <div>
                    <img src="{{ asset('images/admin/tag.png') }}" class="w-100 mb-3" >
                    <div class="text-center">Question Tags</div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-6 col-md-3 col-lg-3">
            <a href="{{ route('test.index') }}">
            <div class="border bg-light p-4 rounded mb-4">
                <div>
                    <img src="{{ asset('images/admin/test.png') }}" class="w-100 mb-3" >
                    <div class="text-center">Tests</div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-6 col-md-3 col-lg-3">
            <a href="{{ route('product.index') }}">
            <div class="border bg-light p-4 rounded mb-4">
                <div>
                    <img src="{{ asset('images/admin/products.png') }}" class="w-100 mb-3" >
                    <div class="text-center">Products</div>
                </div>
            </div>
            </a>
        </div>
        @endif

        @if(\auth::user()->admin!=4)
        <div class="col-6 col-md-3 col-lg-3">
            <a href="{{ route('order.index') }}">
            <div class="border bg-light p-4 rounded mb-4">
                <div>
                    <img src="{{ asset('images/admin/orders.png') }}" class="w-100 mb-3" >
                    <div class="text-center">Orders</div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-6 col-md-3 col-lg-3">
            <a href="{{ route('user.index') }}">
            <div class="border bg-light p-4 rounded mb-4">
                <div>
                    <img src="{{ asset('images/admin/users.png') }}" class="w-100 mb-3" >
                    <div class="text-center">Users</div>
                </div>
            </div>
            </a>
        </div>

        <div class="col-6 col-md-3 col-lg-3">
            <a href="{{ route('form.index') }}">
            <div class="border bg-light p-4 rounded mb-4">
                <div>
                    <img src="{{ asset('images/admin/email.png') }}" class="w-100 mb-3" >
                    <div class="text-center">Forms</div>
                </div>
            </div>
            </a>
        </div>
        @endif

        @if(\auth::user()->admin==1)
        <div class="col-6 col-md-3 col-lg-3">
            <a href="{{ route('coupon.index') }}">
            <div class="border bg-light p-4 rounded mb-4">
                <div>
                    <img src="{{ asset('images/admin/coupon.png') }}" class="w-100 mb-3" >
                    <div class="text-center">Coupons</div>
                </div>
            </div>
            </a>
        </div>
       
         <div class="col-6 col-md-3 col-lg-3">
            <a href="{{ route('admin.analytics') }}">
            <div class="border bg-light p-4 rounded mb-4">
                <div>
                    <img src="{{ asset('images/admin/analytics.png') }}" class="w-100 mb-3" >
                    <div class="text-center">Analytics</div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-6 col-md-3 col-lg-3">
            <a href="{{ route('file.index') }}?type=speaking">
            <div class="border bg-light p-4 rounded mb-4">
                <div>
                    <img src="{{ asset('images/admin/speaking.png') }}" class="w-100 mb-3" >
                    <div class="text-center">Speaking files</div>
                </div>
            </div>
            </a>
        </div>
        @endif

        @if(\auth::user()->admin==4 ||\auth::user()->admin==1)

        <div class="col-6 col-md-3 col-lg-3">
            <a href="{{ route('file.index') }}?type=writing">
            <div class="border bg-light p-4 rounded mb-4">
                <div>
                    <img src="{{ asset('images/admin/writing.png') }}" class="w-100 mb-3" >
                    <div class="text-center">Writing files</div>
                </div>
            </div>
            </a>
        </div>
        @endif

    </div>

        </div>
    </div>

</div>
@endsection
