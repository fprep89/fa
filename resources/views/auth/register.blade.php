@extends('layouts.login')
@section('title', 'Register | First Academy')
@section('description', 'Don\'t have the First Academy account? Create one for FREE.')
@section('keywords', 'Register first academy, signup first academy, registration first academy')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
<form class=" bg-white border rounded p-4" method="POST" action="{{ route('register') }}">
    @csrf

    <h1>Register</h1>
    <hr>
    <div class="form-group row">
        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

        <div class="col-md-8">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Enter your fullname" autofocus>

            @error('name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

        <div class="col-md-8">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter you email (gmail preferred)" >

            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <label for="email" class="col-md-4 col-form-label text-md-right">Phone Number</label>
        @if(isset($message)){{$message }}@endif
        <div class="col-md-8">
            <input id="phone" type="number" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="Enter 10 digit phone number">
        </div>
        @error('phone')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="form-group row">
        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

        <div class="col-md-8">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" name="password" required autocomplete="new-password">

            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
    </div>

    <div class="form-group row">
        <label for="password-confirm" class="col-md-4 col-form-label text-md-right" >{{ __('Confirm Password') }}</label>

        <div class="col-md-8">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="re-enter password" required autocomplete="new-password">
        </div>
    </div>
    <div class="checkbox ml-5">
        <label>
            <input class="form-check-input" type="checkbox" name="terms" id="remember" {{ old('remember') ? 'checked' : '' }}>

            <label class="form-check-label" for="terms">
                I accept <a href="{{ route('terms')}}">Terms</a> and <a href="{{ route('privacy')}}">Privacy</a> Rules
            </label>
        </label>
    </div>
    <div class="checkbox mb-3 ml-3">
        <label>
            <input class="form-check-input" type="checkbox" name="emailss" id="remember" {{ old('remember') ? 'checked' : '' }}>

            <label class="form-check-label" for="emails">
                Send me promotional emails
            </label>
        </label>
    </div>

    <div class="form-group row text-md-left">
        <div class="col-md-4 col-form-label text-md-left">&nbsp;
        </div>
        <div class="col-md-8">
            <button type="submit" class="btn btn-primary">
                {{ __('Register') }}
            </button>
        </div>
    </div>
</form>
        </div>
    </div>
</div>
@endsection
