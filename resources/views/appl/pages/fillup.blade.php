
@extends('layouts.app')
@section('title', 'Fillup layouts | First Academy')
@section('content')


<div class="bg-white">
<div class="card-body p-4 p-md-5 ">
<div class="h1  mb-4 text-center">Fill up - Layouts</div><br>

<div class="item mb-5">
	<h4 class="">Default </h4>
	<div class="row">
		<div class="col-12 col-md-8">
			<img src="{{ asset('images/tests/fillup/default.png')}}" class="w-100 border p-3"/>
		</div>
		<div class="col-12 col-md-4">
			<h5>Details</h5>
			<div class="h5 text-info" style="line-height: 1.5">The image showcases how the fillup would look like in the 'default' layout. The label will not be displayed if the label column is left empty. </div>
		</div>
	</div>
</div>

<div class="item mb-5">
	<h4 class="">IELTS two blanks </h4>
	<div class="row">
		<div class="col-12 col-md-8">
			<img src="{{ asset('images/tests/fillup/ielts_two_blanks.png')}}" class="w-100 border p-3"/>
		</div>
		<div class="col-12 col-md-4">
			<h5>Details</h5>
			<div class="h5 text-info" style="line-height: 1.5">In this layout the label & qno are not displayed. For the two blanks to appear the answer column should follow the format "answer1 [some text] answer2"</div>
		</div>
	</div>
</div>

<div class="item mb-5">
	<h4 class="">Title two blanks </h4>
	<div class="row">
		<div class="col-12 col-md-8">
			<img src="{{ asset('images/tests/fillup/title_two_blanks.png')}}" class="w-100 border p-3"/>
		</div>
		<div class="col-12 col-md-4">
			<h5>Details</h5>
			<div class="h5 text-info" style="line-height: 1.5">In this layout the label & qno are displayed. For the two blanks to appear the answer column should follow the format "answer1 [some text] answer2"</div>
		</div>
	</div>
</div>


<div class="item mb-5">
	<h4 class="">Paragraph </h4>
	<div class="row">
		<div class="col-12 col-md-8">

			<div class="border p-3">
				<h3 class="badge badge-info"> Question Preview</h3>
			<img src="{{ asset('images/tests/fillup/paragraph.png')}}" class="w-100  mb-4"/>
			<h3 class="badge badge-info"> Paragraph Preview</h3>
			<img src="{{ asset('images/tests/fillup/paragraph2.png')}}" class="w-100 "/>
		</div>
		</div>
		<div class="col-12 col-md-4">
			<h5>Details</h5>
			<div class="h5 text-info" style="line-height: 1.5">In this layout, each fillup question will connect to the previous, formaing a paragraph."</div>
		</div>
	</div>
</div>

<div class="item mb-5">
	<h4 class="">Cloze Test </h4>
	<div class="row">
		<div class="col-12 col-md-8">
			<img src="{{ asset('images/tests/fillup/cloze.png')}}" class="w-100 border p-3"/>
		</div>
		<div class="col-12 col-md-4">
			<h5>Details</h5>
			<div class="h5 text-info" style="line-height: 1.5">In this layout, the the answer choices should be entered in label column and seperated by slashes. The question number is prefixed to the dropdown.</div>
		</div>
	</div>
</div>

<div class="item mb-5">
	<h4 class="">Dropdown </h4>
	<div class="row">
		<div class="col-12 col-md-8">
			<img src="{{ asset('images/tests/fillup/dropdown.png')}}" class="w-100 border p-3"/>
		</div>
		<div class="col-12 col-md-4">
			<h5>Details</h5>
			<div class="h5 text-info" style="line-height: 1.5">In this layout, the the answer choices should be entered in label column and seperated by slashes. The question number is placed left side of prefix.</div>
		</div>
	</div>
</div>

</div>		
</div>
@endsection           