@extends('layouts.main')

@section('title', 'Bank Reconcilation')

@section('style')

.panel-heading.collapsed .fa-angle-double-up,
.panel-heading .fa-angle-double-down {
  display: none;
}

.panel-heading.collapsed .fa-angle-double-down,
.panel-heading .fa-angle-double-up {
  display: inline-block;
}

i.fa {
  cursor: pointer;
  float: right;
 <!--  margin-right: 5px; -->
}

.collapsed ~ .panel-body {
  padding: 0;
}

.clearfix {
	overflow: auto;
}
@endsection


@section('body')

<input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

@if (Request::get('scope') == 'ALL')
	<input id="recstatus_use" name="recstatus_use" type="hidden" value="ALL">
@else
	<input id="recstatus_use" name="recstatus_use" type="hidden" value="{{Request::get('scope')}}">
@endif

<!--***************************** Search + table ******************-->
	 
<div class='row'>
	<div class="panel panel-default" style="margin: 10px; height: 55px;">
  	<div class="panel-body" style="padding:10px 50px">
			<button id="newReconBtn" type="button" class="btn btn-primary" style="">
		  New</button>
			<button id="searchReconBtn" type="button" class="btn btn-primary" style="">
		  Search</button>
			<button id="addReconBtn" type="button" class="btn btn-info" style="display: none;">
		  Recon</button>
			<!-- <button id="saveReconBtn" type="button" class="btn btn-primary" style="display: none;">
		  Save</button> -->
			<button id="cancelReconBtn" type="button" class="btn btn-primary" style="display: none;">
		  Cancel</button>
  	</div>
	</div>

	<div class="panel panel-default" style="margin: 10px; height: 88vh;" id="panel_default_c">
  	<div class="panel-body">
  		<div class='col-md-12' style="padding:0 0 15px 0">
	  		<form id="formdata2">
	  			<input type="hidden" id="bankcode_">
  				<div class="col-md-2" id="bankcode_div_new" >
	  				<label for="bankcode1">Bank</label> 
		 				<div class='input-group'>
							<input id="bankcode1" name="bankcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" disabled>
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	  				</div>
	 					<span class="help-block"></span>
					</div>
					<div class="col-md-2" id="bankcode_div_search" style="display:none">
	  				<label for="bankcode2">Bank</label> 
		 				<div class='input-group'>
							<input id="bankcode2" name="bankcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" disabled>
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	  				</div>
	 					<span class="help-block"></span>
					</div>
					<a class='pull-right pointer text-primary' style="padding-left: 30px" id='pdfgen1' target="_blank">
				    <span class='fa fa-print'></span> Print 
					</a>
	 			</form>
  			<div class="col-md-4" style="padding-left: 0px;">
  				<label for="bankname">&nbsp;</label> 
					<input id="bankname" name="bankname" type="text" class="form-control input-sm text-uppercase" readonly>
				</div>
  		</div>
  		<div class='col-md-2' style="padding:0 0 15px 0">
  			<form id="formdata">
	  			<div class="col-md-12" >
	  				<label for="recdate">Date</label> 
						<input id="recdate" name="recdate" type="date" class="form-control input-sm text-uppercase" data-validation="required" disabled>
					</div>
	  			<div class="col-md-12" style="padding:15px 15px 5px 15px">
	  				<label for="closeAmtStamnt">Closing Bank Statement</label> 
						<input id="closeAmtStamnt" name="closeAmtStamnt" type="text" class="form-control input-sm text-uppercase" data-validation="required" disabled>
					</div>
	  			<div class="col-md-12" style="padding:15px 15px 5px 15px">
	  				<label for="cashBkBal">Cash Book Balance</label> 
						<input id="cashBkBal" name="cashBkBal" type="text" class="form-control input-sm text-uppercase" readonly>
					</div>
	  			<div class="col-md-12" style="padding:15px 15px 5px 15px">
	  				<label for="unReconAmt">Unreconciled Amount</label> 
						<input id="unReconAmt" name="unReconAmt" type="text" class="form-control input-sm text-uppercase" readonly>
					</div>
				</form>
  		</div>
  		<div class='col-md-10' style="padding:0 0 15px 0">
  			<table id="jqGrid" class="table table-striped"></table>
  			<div id="jqGridPager"></div>
  			<div class='col-md-12'>
  				<span style="
							width: 80px;
							position: absolute;
							width: 140px;
							right: 10px;
							top: 5px;
						">
		  			<label for="cr_tot" style="margin:0">Total Credit</label> 
						<input id="cr_tot" name="cr_tot" type="text" class="form-control input-sm text-uppercase" readonly style="height: 25px;padding: 3px 8px;">
  				</span>
  				<span style="
							width: 80px;
							position: absolute;
							width: 140px;
							right: 160px;
							top: 5px;
						">
  					<label for="db_tot" style="margin:0">Total Debit</label> 
						<input id="db_tot" name="db_tot" type="text" class="form-control input-sm text-uppercase" readonly style="height: 25px;padding: 3px 8px;">
  				</span>
				</div>
  		</div>
  	</div>
	</div>
</div>

	<!-- ***************End Search + table ********************* -->
		<div id="dialogForm" title="Add Form" >
			<div class='col-md-12' style="padding:0px">
  				<label for="clsBnkStatmnt">Closing Bank Statement  <span style="padding-left: 20px;">Date: <span id="spanrecdate"></span></span></label> 
					<input id="clsBnkStatmnt" name="clsBnkStatmnt" type="text" class="form-control input-sm text-uppercase" style="width: 30%;margin-bottom: 10px;">

					<select class="form-control" id="alloState" style="width: 140px;
				    position: absolute;
				    top: 20px;
				    right: 370px;">
						<option value="cashbook" selected>Cash Book</option>
						<option value="bankstmnt" >Bank Statement</option>
  				</select>
  				<select class="form-control" id="alloCol" style="width: 140px;
				    position: absolute;
				    top: 20px;
				    right: 220px;">
						<option value="postdate" >Date</option>
						<option value="reference" selected>Reference</option>
						<option value="amount" >Amount</option>
						<option value="source" >Source</option>
						<option value="trantype" >Trantype</option>
						<option value="auditno" >Auditno</option>
						<option value="pvno" >PV No</option>
					</select>
					<input id="alloText" placeholder="Search Here.." type="text" class="form-control input-sm" style="width: 200px;
				    position: absolute;
				    top: 20px;
				    right: 10px;">
					<input id="alloDate" type="date" class="form-control input-sm" style="width: 200px;
				    position: absolute;
				    top: 20px;
				    right: 10px;
				    display: none;">
			</div>
			<div class='col-md-6' id="jqGrid2_c" style="padding:0 0 15px 0">
				<button id="btn_cbrecdtl_del"  type="button" class="btn btn-default" style="
					position: absolute;
			    right: 10px;
			    top: -30px;
			    padding-bottom: 3px;"><i class="fa fa-chevron-right" aria-hidden="true" style="float: right;padding-top: 2px;"></i><b style="padding: 0px 5px;">Delete</b></button>
				<span><b>Bank Statement</b></span>
  			<table id="jqGrid2" class="table table-striped"></table>
  			<div id="jqGrid2Pager"></div>
  			<div class='col-md-12'>
  				<span style="
							width: 80px;
							position: absolute;
							width: 140px;
							right: 10px;
							top: 5px;
						">
		  			<label for="all_tot" style="margin:0">Total Amount</label> 
						<input id="all_tot" name="all_tot" type="text" class="form-control input-sm text-uppercase" readonly style="height: 25px;padding: 3px 8px;">
  				</span>
				</div>
			</div>
			<div class='col-md-6' id="jqGrid3_c" style="padding:0 0 15px 0">
				<button id="btn_cbrecdtl_add" type="button" class="btn btn-default" style="
					position: absolute;
			    left: 10px;
			    top: -30px;
			    padding-bottom: 3px;"><i class="fa fa-chevron-left" aria-hidden="true" style="float: left;padding-top: 2px;"></i><b style="padding: 0px 5px;">Add</b></button>
				<span><b>Cash Book</b></span>
  			<table id="jqGrid3" class="table table-striped"></table>
  			<div id="jqGrid3Pager"></div>
			</div>
		</div>


@endsection

@section('scripts')
	<script type="text/javascript">
		var bankcode_obj = [
			@foreach($bank as $key => $val) 
				{
					bankname:'{{$val->bankname}}',
					bankcode:'{{$val->bankcode}}'
				},
			@endforeach 
		];

		$(document).ready(function () {
			if(!$("table#jqGrid").is("[tabindex]")){
				$("#jqGrid").bind("jqGridGridComplete", function () {
					$("table#jqGrid").attr('tabindex',3);
					$("td#input_jqGridPager input.ui-pg-input.form-control").attr('tabindex',4);
					$("td#input_jqGridPager input.ui-pg-input.form-control").on('focus',onfocus_pageof);
					if($('table#jqGrid').data('enter')){
						$('td#input_jqGridPager input.ui-pg-input.form-control').focus();
						$("table#jqGrid").data('enter',false);
					}

				});
			}

			function onfocus_pageof(){
				$(this).keydown(function(e){
					var code = e.keyCode || e.which;
					if (code == '9'){
						e.preventDefault();
						$('input[name=Stext]').focus();
					}
				});

				$(this).keyup(function(e) {
					var code = e.keyCode || e.which;
					if (code == '13'){
						$("table#jqGrid").data('enter',true);
					}
				});
			}
			
		});
	</script>
	<script src="js/finance/CM/bankRecon/bankRecon.js?v=1.8"></script>
@endsection