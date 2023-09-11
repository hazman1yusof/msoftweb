@extends('layouts.main')


@section('style')

.imgcontainer {
  position: relative;
  width: fit-content;
}

.imgcontainer img {
}

.imgcontainer .btn {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  background-color: #55554452;
  color: white;
  font-size: 16px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  text-align: center;
}

.imgcontainer .btn:hover {
  background-color: black;
}

#bio .col-md-1 {
  width: 58px;
} 

@endsection

@section('content')

<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
<input type="hidden" name="loginid" id="loginid" value="{{Auth::user()->username}}">

<div class="ui teal segment" style="padding-bottom: 30px;">
	<h4 class="ui header">Upload an Image</h4>
	<table class="ui celled table" id='episodeList'>
		<thead>
			<tr>
				<th width="20%">Login ID</th>
				<th width="40%">Name</th>
				<th>Department</th>
				<th width="25%">Upload</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{{Auth::user()->username}}</td>
				<td>{{Auth::user()->name}}</td>
				<td>PATHLAB</td>
				<td>
        			<small id="remark_" style="display: none">Filename<br></small>
					<form class="upload_form ui input" id="formdata" method="post" action="./labresult" enctype="multipart/form-data">
						{{csrf_field()}}

        				<input type="text" id="rename" name='rename' class="ui input" placeholder="Filename" style="display: none">

						<button type="button" id='click' class='ui icon button orange btn' ><i class='cloud upload icon' ></i></button>

						<button type="button" id="cancel" oper='cancel' class='ui icon small red button btn' style="margin-left:5px;display: none;"><i class='times icon'></i></button>
						<button type="submit" id="submit" oper='submit' class='ui icon small green button btn' style="display:none;"><i class='check icon'></i></button>

						<input type="file" name="file" id="file" accept=".pdf, .xls, .xlsx, .csv" style="display: none;">

					</form>
				</td>
			</tr>
		</tbody>
	</table>
</div>


<div class="ui teal segment" style="padding-bottom: 30px;">
<h4 class="ui header">This user uploaded Images</h4>
	<table class="ui celled table" id='tablePreview'>
		<thead>
			<tr>
				<th>ID</th>
				<th>Date</th>
				<th>Time</th>
				<th>File Name</th>
				<th>File Preview</th>
				<th>Login ID</th>
				<th>Add User</th>
				<th>Download</th>
				<th>type</th>
			</tr>
		</thead>
	</table>
</div>

<!-- <div class="ui mini modal">
  <i class="close icon"></i>
  <div class="image content">
    <div class="description">
      <div class="ui header">We've auto-chosen a profile image for you.</div>
      <p>We've grabbed the following image from the <a href="https://www.gravatar.com" target="_blank">gravatar</a> image associated with your registered e-mail address.</p>
      <p>Is it okay to use this photo?</p>
    </div>
  </div>
  <div class="actions">
    <div class="ui black deny button">
      Nope
    </div>
    <div class="ui positive right labeled icon button">
      Yep, that's me
      <i class="checkmark icon"></i>
    </div>
  </div>
</div> -->

@endsection

@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.semanticui.min.css">
@endsection

@section('js')
	<script src="{{ asset('js/labresult.js') }}"></script>
	<script src="{{ asset('assets/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
@endsection