@extends('layouts.main')

@section('title', 'Race Setup')

@section('body')

<form id='testform'>
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
	<input type="date" name="date" id="date">
	<input type="test" name="quan" id="quan">

	<input type="button" name="submit" id="submit" value="submit">
</form>	

@endsection


@section('scripts')

	<script src="js/test/test.js"></script>
	
@endsection