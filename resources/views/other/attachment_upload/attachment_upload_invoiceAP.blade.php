@extends('patientcare.layouts.main')


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
<input type="hidden" name="idno" id="my_idno" value="{{Request::get('idno')}}">
<input type="hidden" name="type" id="my_page" value="{{Request::get('page')}}">

<div class="ui teal segment" style="padding-bottom: 30px;">
	<h4 class="ui header">Upload an Attachment for invoice</h4>
	<table class="ui celled table" id='episodeList'>
		<thead>
			<tr>
				<th width="5%">Auditno</th>
				<th width="5%">TT</th>
				<th>Creditor</th>
				<th>Document Date</th>
				<th>Post Date</th>
				<th>Document No.</th>
				<th>Amount</th>
				<th>Outstanding</th>
				<th>Status</th>
				<th>Remark</th>
				<th width="25%">Upload</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>{{$invoice->auditno}}</td>
				<td>{{$invoice->trantype}}</td>
				<td>{{$invoice->payto}}</td>
				<td>{{$invoice->actdate}}</td>
				<td>{{$invoice->postdate}}</span></td>
				<td>{{$invoice->document}}</td>
				<td>{{$invoice->amount}}</td>
				<td>{{$invoice->outamount}}</td>
				<td>{{$invoice->recstatus}}</td>
				<td>{{$invoice->remarks}}</td>
				<td>
        			<small id="remark_" style="display: none">Remark<br></small>
					<form class="upload_form ui input" id="formdata" method="post" action="./attachment_upload/form" enctype="multipart/form-data">
						{{csrf_field()}}

        				<input type="hidden" name='page' value="{{Request::get('page')}}">
        				<input type="hidden" name='idno' value="{{Request::get('idno')}}">
        				<input type="text" id="rename" name='rename' class="ui input" placeholder="Remark" style="display: none">

						<button type="button" id='click' class='ui icon button orange btn' ><i class='cloud upload icon' ></i></button>

						<button type="button" id="cancel" oper='cancel' class='ui icon small red button btn' style="margin-left:5px;display: none;"><i class='times icon'></i></button>
						<button type="submit" id="submit" oper='submit' class='ui icon small green button btn' style="display:none;"><i class='check icon'></i></button>

						<input type="file" name="file" id="file" accept="audio/*,image/*,video/*,application/pdf" style="display: none;">

					</form>
				</td>
			</tr>
		</tbody>
	</table>
</div>


<div class="ui teal segment" style="padding-bottom: 30px;">
<h4 class="ui header">This invoice uploaded Attachment</h4>
	<table class="ui celled table" id='tablePreview'>
		<thead>
			<tr>
				<th>ID</th>
				<th>Date</th>
				<th>Remark</th>
				<th>File Preview</th>
				<th>MRN</th>
				<th>Add User</th>
				<th>Add Date</th>
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
	<link rel="stylesheet" type="text/css" href="{{ asset('patientcare/assets/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.semanticui.min.css">
@endsection

@section('js')
	<script src="{{ asset('js/other/attachment_upload/attachment_upload_invoiceAP.js') }}"></script>
	<script src="{{ asset('patientcare/assets/DataTables/datatables.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.js"></script>
@endsection