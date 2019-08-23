@extends('layouts.app')
@section('content')
<div class="bg-white">
	<div class="card-body p-4 ">
		<h1 class="text-primary"><i class="fa fa-check-circle"></i> Email Notify</h1>
		<hr>
		<p> Test review notification email is sent to {{$user->name }} ({{$user->email}})  </p>
		<hr>
		<a href="{{ url()->previous() }}">
			<button class="btn btn-outline-primary btn-sm"> Back</button>
		</a>
	</div>
</div>
@endsection