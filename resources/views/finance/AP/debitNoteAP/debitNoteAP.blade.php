@extends('layouts.main')

@section('title', 'Debit Note')

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

.whtspc_wrap{
	white-space: pre-wrap !important;
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
		<form id="searchForm" class="formclass" style='width:99%; position:relative' onkeydown="return event.key != 'Enter';">
			<fieldset>
			<input id="getYear" name="getYear" type="hidden"  value="{{Carbon\Carbon::now()->year}}">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
					  	<div class="col-md-2">
					  		<label class="control-label" for="Scol">Search By : </label>  
					  			<select id='Scol' name='Scol' class="form-control input-sm" tabindex="1"></select>
		             	</div>

						 <div class="col-md-5">
					  		<label class="control-label"></label>  
							<input style="display:none" name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">

							<div id="creditor_text">
								<div class='input-group'>
									<input id="creditor_search" name="creditor_search" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span id="creditor_search_hb" class="help-block"></span>
							</div>

							<div id="actdate_text" class="form-inline" style="display:none">
								FROM DATE <input id="actdate_from" type="date" placeholder="FROM DATE" class="form-control text-uppercase">
								TO <input id="actdate_to" type="date" placeholder="TO DATE" class="form-control text-uppercase" >
								<button type="button" class="btn btn-primary btn-sm" id="actdate_search">SEARCH</button>
							</div>
							
						</div>
		            </div>
				</div>

				<div class="col-md-2">
				  	<label class="control-label" for="Status">Status</label>  
					  	<select id="Status" name="Status" class="form-control input-sm">
					      <option value="All" selected>ALL</option>
					      <option value="Open">OPEN</option>
					      <option value="Posted">POSTED</option>
					      <option value="Cancelled">CANCELLED</option>
					    </select>
	            </div>

				<?php 
					$scope_use = 'posted';

					if(Request::get('scope') == 'ALL'){
						$scope_use = 'posted';
					}else if(Request::get('scope') == 'CANCEL'){
						$scope_use = 'cancel';
					}
				?>

				<div id="div_for_but_post" class="col-md-8 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<span id="error_infront" style="color: red"></span>
					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>
					<!-- <button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button> -->
					<button 
					type="button" 
						class="btn btn-primary btn-sm" 
						id="but_post_jq" 
						data-oper="{{$scope_use}}" 
						style="display: none;">
						@if (strtoupper(Request::get('scope')) == 'ALL')
							{{'POST'}}
						@else
							{{Request::get('scope')}}
						@endif
					</button>

					<!-- <button type="button" class="btn btn-primary btn-sm" id="but_post_single_jq" data-oper="posted" style="display: none;">
						@if (strtoupper(Request::get('scope')) == 'ALL')
							{{'POST'}}
						@else
							{{Request::get('scope')}}
						@endif
					</button>

					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
					<button type="button" class="btn btn-default btn-sm" id="but_soft_cancel_jq" data-oper="soft_cancel" style="display: none;">CANCEL</button> -->
				</div>

			</fieldset> 
		</form>

		<div class="panel panel-default" id="sel_tbl_panel" style="display:none">
    		<div class="panel-heading heading_panel_">List Of Selected Item</div>
    		<div class="panel-body">
    			<div id="sel_tbl_div" class='col-md-12' style="padding:0 0 15px 0">
    				<table id="jqGrid_selection" class="table table-striped"></table>
    				<div id="jqGrid_selectionPager"></div>
				</div>
    		</div>
		</div>
		 
		<div class="panel panel-default">
		    <div class="panel-heading">Debit Note Header</div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid" class="table table-striped"></table>
            			<div id="jqGridPager"></div>
        			</div>
		    	</div>
		</div>


	    <div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel" id="panel_jqGrid3">
				<b>DOCUMENT NO: </b><span id="trantype_show"></span> - <span id="document_show"></span><span id="ifcancel_show" style="color: red;"></span><br>
				<b>SUPPLIER NAME: </b><span id="suppcode_show"></span>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Debit Note Detail</h5>
				</div>
			</div>
			<div id="jqGrid3_panel" class="panel-collapse collapse">
				<div class="panel-body">
					@if (strtoupper(Request::get('scope')) == 'CANCEL')
						<button 
								type="button" 
								class="btn btn-danger btn-sm" 
								id="but_post2_jq"
								data-oper="cancel"
								style="float: right;margin: 0px 20px 10px 20px;">
								Cancel Debit Note
						</button>
					@endif
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3" class="table table-striped"></table>
						<div id="jqGridPager3"></div>
					</div>
				</div>
				
			</div>
		</div>
		
		<div class="panel panel-default" style="position: relative;" id="jqGridAPAlloc_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGridAPAlloc_panel" id="panel_gridpv">
			<b>DOCUMENT NO: </b><span id="pvTrantype_show"></span> - <span id="pvDocument_show"></span><span id="ifcancel_showpv" style="color: red;"></span><br>
			<b>SUPPLIER NAME: </b><span id="pvSuppcode_show"></span>
				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>PV Detail</h5>
				</div>
			</div>

			<div id="jqGridAPAlloc_panel" class="panel-collapse collapse">
				<div class="panel-body">
	    			<div id="" class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGridAlloc" class="table table-striped"></table>
            			<div id="jqGridPagerAPAlloc"></div>
    				</div>
	    		</div>
			</div>	
			
		</div>

    </div>
	
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Debit Note AP Header
			<a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span> Print </a>
			</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata' autocomplete="off">
					{{ csrf_field() }}
					
					<input id="auditno" name="auditno" type="hidden">
					<input id="apacthdr_idno" name="apacthdr_idno" type="hidden">
					<input id="apacthdr_source" name="apacthdr_source" type="hidden" value="{{$_GET['source']}}">
					<input id="apacthdr_trantype" name="apacthdr_trantype" type="hidden" value="{{$_GET['trantype']}}">

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_doctype">Doc Type</label> 
							<div class="col-md-3">
							  	<select id="apacthdr_doctype" name=apacthdr_doctype class="form-control" data-validation="required" data-validation-error-msg="Please Enter Value">
							       <option value="Debit_Note">Debit Note</option>
							    </select>
						  	</div>

				  		<label class="col-md-2 control-label" for="apacthdr_auditno">Audit No</label>  
				  			<div class="col-md-3">
				  				<input id="apacthdr_auditno" name="apacthdr_auditno" type="text" class="form-control input-sm" rdonly>
				  		</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_suppcode">Creditor</label>	 
						 	<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_suppcode" name="apacthdr_suppcode" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>

						<label class="col-md-2 control-label" for="apacthdr_actdate">Doc Date</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_actdate" name="apacthdr_actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" max="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>

					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_payto">Pay To</label>	  
							<div class="col-md-3">
							  	<div class='input-group'>
									<input id="apacthdr_payto" name="apacthdr_payto" type="text" maxlength="12" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  	</div>
							  	<span class="help-block"></span>
						  	</div>

						<label class="col-md-2 control-label" for="apacthdr_postdate">Post Date</label>  
							<div class="col-md-3">
								<input id="apacthdr_postdate" name="apacthdr_postdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
							</div>
					</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_document">Document No</label>  
				  			<div class="col-md-3">
								<input id="apacthdr_document" name="apacthdr_document" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" data-show_error=''>
				  			</div>
					</div>

					<div class="form-group">
			    		<label class="col-md-2 control-label" for="apacthdr_remarks">Remarks</label> 
			    			<div class="col-md-8"> 
			    				<textarea class="form-control input-sm text-uppercase" name="apacthdr_remarks" rows="2" cols="55" maxlength="400" id="apacthdr_remarks" data-validation="required" data-validation-error-msg="Please Enter Value"></textarea>
			    			</div>
			   		</div>

					<div class="form-group">
						<label class="col-md-2 control-label" for="apacthdr_amount">Amount</label>  
					  		<div class="col-md-3">
								<input id="apacthdr_amount" name="apacthdr_amount" maxlength="12" class="form-control input-sm" rdonly> 
		 					</div>

					</div>

				</form>
				<div class="panel-body">
				    <div class="noti" style="font-size: bold; color: red"><ol></ol></div>
			    </div>
			</div>
		</div>
		<div class='panel panel-info' id="grid_detail">
			<div class="panel-heading">Debit Note Detail</div>
				<div class="panel-body">
					<form id='formdata2' class='form-vertical' style='width:99%'>
						<div id="jqGrid2_c" class='col-md-12'>
							<table id="jqGrid2" class="table table-striped"></table>
						        <div id="jqGridPager2"></div>
						</div>
					</form>
				</div>
		</div>	
       
	</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
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

	<script src="js/finance/AP/debitNoteAP/debitNoteAP.js?v=1.2"></script>
	<!-- <script src="js/finance/AP/debitNoteAP/pdfgen.js"></script>-->
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script> 
	
@endsection