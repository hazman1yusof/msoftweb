@extends('patientcare.layouts.main')


@section('style')

body{
  font-size: 12px !important;
  overflow-y:hidden !important;
}

.container_sem {
    padding-left: 15px;
    padding-top: 0px !important;
}

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

th {
  font-size: 12px !important;
}

#bio .col-md-1 {
  width: 58px;
} 

@endsection

@section('content')

<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

<div class="ui teal segment">
	<form class="upload_form ui input" id="formdata" method="post" action="./attachment_upload/form" enctype="multipart/form-data" style="position: absolute;
    z-index: 1999;">
		  {{csrf_field()}}
      <input type="hidden" name="idno" id="my_idno" value="{{Request::get('idno')}}">
      <input type="hidden" name="page" id="my_page" value="{{Request::get('page')}}">
	    <input type="text" id="rename" name='rename' class="ui input" placeholder="Rename" style="display: none">
	    <button type="button" id='click' class='ui icon button orange btn' ><i class='cloud upload icon' ></i></button>
	    <button type="button" id="cancel" class='ui icon small red button btn' style="margin-left:5px;display: none;"><i class='times icon'></i></button>
	    <button type="submit" id="submit" class='ui icon small green button btn' style="display:none;"><i class='check icon'></i></button>

	    <input type="file" name="file" id="file" accept="audio/*,image/*,video/*,application/pdf" style="display: none;">

	</form>
	<table class="ui celled table" id='tablePreview'>
	<thead>
			<tr>
				<th>ID</th>
				<th>Date</th>
				<th>Remark</th>
				<th>File Preview</th>
				<th>Delete</th>
				<th>Add User</th>
				<th>Add Date</th>
				<th>Download</th>
				<th>type</th>
        <th>auditno</th>
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
	<link rel="stylesheet" type="text/css" href="{{ asset('patientcare/assets/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.semanticui.min.css">
@endsection

@section('js')
	<script src="{{ asset('patientcare/assets/DataTables/datatables.min.js') }}"></script>
  <script src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
  <script src="{{ asset('js/other/attachment_upload/attachment_upload.js?v=1.2') }}"></script>
@endsection