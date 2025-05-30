@extends('layouts.main')

@section('title', 'Journal Entry')

@section('style')

<!-- body {
	font-family: "Helvetica Neue",Helvetica,Arial,sans-serif !important;;
  font-size: 12px !important;
}
 -->
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

@endsection

@section('body')

	<!-- @include('layouts.default_search_and_table') -->
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
							<input  name="Stext" type="search" seltext='true' placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">
						</div>
		         </div>
				</div>

				<!-- <div class="col-md-3" style="padding: 10px;">
					&nbsp;
	            </div> -->

				<div class="col-md-2">
				  	<label class="control-label" for="Status">Status</label>  
					  	<select id="Status" name="Status" class="form-control input-sm">
					      <option value="All" selected>ALL</option>
					      <option value="Open">OPEN</option>
					      <option value="Posted">POSTED</option>
					      <option value="Cancelled">CANCELLED</option>
					    </select>
	            </div>

				<div id="div_for_but_post" class="col-md-8 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<span id="error_infront" style="color: red"></span>
					<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>
					<button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>
					<button 
					type="button" 
						class="btn btn-primary btn-sm" 
						id="but_post_jq" 
						data-oper="posted" 
						style="display: none;">
						@if (strtoupper(Request::get('scope')) == 'ALL')
							{{'POST'}}
						@else
							{{Request::get('scope').' ALL'}}
						@endif
					</button>

					<button type="button" class="btn btn-primary btn-sm" id="but_post_single_jq" data-oper="posted" style="display: none;">
						@if (strtoupper(Request::get('scope')) == 'ALL')
							{{'POST'}}
						@else
							{{Request::get('scope')}}
						@endif
					</button>

					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
					<button type="button" class="btn btn-default btn-sm" id="but_soft_cancel_jq" data-oper="soft_cancel" style="display: none;">CANCEL</button>
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
		    <div class="panel-heading">Journal Entry Header
				<a class='pull-right pointer text-primary' style="padding-left: 30px" id='pdfgen2' href="" target="_blank">
					<span class='fa fa-print'></span> Print 
				</a>
		    </div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid" class="table table-striped"></table>
            			<div id="jqGridPager"></div>
        			</div>
		    	</div>
		</div>


	    <div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel" id="panel_jqGrid3">
				<b>DOCUMENT NO: </b><span id="trantype_show"></span> - <span id="auditno_show"></span><span id="ifcancel_show" style="color: red;"></span><br>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Journal Entry Detail</h5>
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
								Cancel Invoice
						</button>
					@endif
					<div class='col-md-12' style="padding:0 0 15px 0">
						<table id="jqGrid3" class="table table-striped"></table>
						<div id="jqGridPager3"></div>
					</div>
				</div>
				
			</div>
		</div>   
  </div>
	
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Journal Entry Header
				<a class='pull-right pointer text-primary' id='pdfgen1'><span class='fa fa-print'></span> Print </a>
			</div>
			<div class="panel-body" style="position: relative;">
				<form class='form-horizontal' style='width:99%' id='formdata'>
					{{ csrf_field() }}
					
					<input id="auditno" name="auditno" type="hidden">
					<input id="gljnlhdr_idno" name="gljnlhdr_idno" type="hidden">
					<input id="gljnlhdr_source" name="gljnlhdr_source" type="hidden" value="GL">
					<input id="gljnlhdr_trantype" name="gljnlhdr_trantype" type="hidden" value="JNL">

				
					<div class="form-group">
				  		<label class="col-md-2 control-label" for="gljnlhdr_auditno">Audit No</label>  
				  			<div class="col-md-3">
				  				<input id="gljnlhdr_auditno" name="gljnlhdr_auditno" type="text" class="form-control input-sm" rdonly>
				  		</div>

                        <label class="col-md-2 control-label" for="gljnlhdr_docdate">Date</label>  
				  			<div class="col-md-3">
								<input id="gljnlhdr_docdate" name="gljnlhdr_docdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" max="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  			</div>
					</div>

                    <div class="form-group">
                        <label class="col-md-2 control-label" for="gljnlhdr_description">Description</label> 
			    			<div class="col-md-8"> 
			    				<textarea id="gljnlhdr_description" class="form-control input-sm text-uppercase" name="gljnlhdr_description" rows="2" cols="55" maxlength="400" data-validation="required" data-validation-error-msg="Please Enter Value"></textarea>
			    			</div>
					</div>

                    <div class="form-group">
						<label class="col-md-2 control-label" for="gljnlhdr_period">Period</label>  
				  			<div class="col-md-3">
								<input id="gljnlhdr_period" name="gljnlhdr_period" type="text" maxlength="10" class="form-control input-sm" rdonly>
				  			</div>

				  		<label class="col-md-2 control-label" for="gljnlhdr_year">Year</label>	  
				  			<div class="col-md-3">
                                <input id="gljnlhdr_year" name="gljnlhdr_year" type="text" maxlength="10" class="form-control input-sm" rdonly>
						  	</div>
					</div>

                    <hr/>

                    <div class="form-group">
						<label class="col-md-2 control-label" for="gljnlhdr_creditAmt">Credit</label>  
				  			<div class="col-md-2">
								<input id="gljnlhdr_creditAmt" name="gljnlhdr_creditAmt" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-sanitize-number-format="0,0.00" rdonly>
				  			</div>

				  		<label class="col-md-2 control-label" for="gljnlhdr_debitAmt">Debit</label>	  
				  			<div class="col-md-2">
                                <input id="gljnlhdr_debitAmt" name="gljnlhdr_debitAmt" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-sanitize-number-format="0,0.00" rdonly>
						  	</div>

                        <label class="col-md-2 control-label" for="gljnlhdr_different">Different</label>	  
				  			<div class="col-md-2">
                                <input id="gljnlhdr_different" name="gljnlhdr_different" type="text" maxlength="30" class="form-control input-sm text-uppercase" data-sanitize-number-format="0,0.00" rdonly>
						  	</div>
					</div>
				</form>
				<div class="panel-body">
					<div class="noti2" style="font-size: bold; color: red"><ol></ol></div>
			</div>
			</div>
		</div>
			

		<div class='panel panel-info'>
			<div class="panel-heading">Journal Entry Detail</div>
				<div class="panel-body">
					<form id='formdata2' class='form-vertical' style='width:99%'>
						<div id="jqGrid2_c" class='col-md-12'>
							<table id="jqGrid2" class="table table-striped"></table>
						        <div id="jqGridPager2"></div>
						</div>
					</form>
				</div>

				<div class="panel-body">
					<div class="noti" style="font-size: bold; color: red"><ol></ol></div>
				</div>
			</div>
		</div>			
			
	</div>
@endsection


@section('css')
	<link rel="stylesheet" type="text/css" href="{{ asset('patientcare/assets/DataTables/datatables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.13/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.semanticui.min.css">
@endsection

@section('js')
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

	<script src="js/finance/GL/journalEntry/journalEntry.js?v=1.3"></script>
	<!-- <script src="js/finance/AP/invoiceAP/pdfgen.js"></script> -->
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
@endsection