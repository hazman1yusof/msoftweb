

@extends('layouts.main')

@section('title', 'num2words')

@section('body')
<form id='testform' class="col-md-8" style="padding: 10px">
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
	<div class='input-group form-group'>
		
	<div id="demo">
		  Enter amount:  <input id="num" type="text">
		  <input id="trans" type="button" value="Convert to words">
		  <div></div>
		</div>

@endsection


@section('scripts')

	<script src="js/test/num2words.js"></script>
	<script src="plugins/jQuerynum2words/jquery.num2words.js"></script>
	
@endsection