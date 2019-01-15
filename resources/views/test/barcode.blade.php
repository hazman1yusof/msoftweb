@extends('layouts.main')

@section('title', 'Barcode')

@section('body')
<form id='testform' class="col-md-8" style="padding: 10px">
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
	<div class='input-group form-group'>
		<input id="itemcode" name="itemcode" type="text" maxlength="23" class="form-control input-sm">
		<input id="desc" name="desc" type="hidden" >
		<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	</div>
	<span class="help-block"></span>
	<input type="button" name="submit" id="submit" value="submit" class="btn btn-primary btn-sm">
</form>
<hr>

<script language="javascript">var p = false;</script>
<form method="post" onsubmit = "return(false)">
    <input type = "text" name = "text" />
    <input type = "submit" value = "submit" name = "submit" onClick = "javascript: p=true;" />
</form>

@endsection


@section('scripts')

	<script src="js/test/barcode.js"></script>
	
@endsection