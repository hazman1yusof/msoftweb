@extends('layouts.main')

@section('title', 'Bank In Registration')

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

							<div id="bankcode_text">
								<div class='input-group'>
									<input id="bankcode_search" name="bankcode_search" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span id="bankcode_search_hb" class="help-block"></span>
							</div>

							<div id="creditor_text" style="display:none">
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

				<!-- <div id="div_for_but_post" class="col-md-10 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<button type="button" class="btn btn-primary btn-sm" id="but_post_jq" data-oper="posted" style="display: none;">POST</button>
					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
				</div> -->
				<div class="col-md-2">
					<label class="control-label" for="Status">Status</label>  
					<select id="Status" name="Status" class="form-control input-sm">
							@if (Request::get('scope') == 'ALL')
							<option value="All" selected>ALL</option>
							<option value="OPEN">OPEN</option>
							<option value="POSTED">POSTED</option>
							<option value="CANCELLED">CANCELLED</option>
							@elseif (Request::get('scope') == 'OPEN')
								<option value="OPEN">OPEN</option>
							@elseif (Request::get('scope') == 'POSTED')
								<option value="POSTED">POSTED</option>
							@elseif (Request::get('scope') == 'CANCEL')
								<option value="OPEN">OPEN</option>
							@endif
				   	</select>
	      		</div>

				<?php 
					$scope_use = 'posted';

					if(Request::get('scope') == 'ALL'){
						$scope_use = 'posted';
					}else if(Request::get('scope') == 'DELIVERED'){
						$scope_use = 'delivered';
					}else if(Request::get('scope') == 'REOPEN'){
						$scope_use = 'reopen';
					}else if(Request::get('scope') == 'CANCEL'){
						$scope_use = 'cancel';
					}
				?>

			<div id="div_for_but_post" class="col-md-6 col-md-offset-6" style="padding-top: 20px; text-align: end;">
				
				<span id="error_infront" style="color: red"></span>
				<button style="display:none" type="button" id='show_sel_tbl' data-hide='true' class='btn btn-info btn-sm button_custom_hide' >Show Selection Item</button>
				<!-- <button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button> -->

				@if (Request::get('scope') != 'ALL' && Request::get('scope') != 'REOPEN' && Request::get('scope') != 'CANCEL')
				<button type="button" class="btn btn-danger btn-sm" id="but_cancel_jq" data-oper="reject" style="display: none;">REJECT</button>
				@endif

				<button 
				type="button" 
					class="btn btn-primary btn-sm" 
					id="but_post_jq" 
					data-oper="{{$scope_use}}" 
					style="display: none;">
					@if (strtoupper(Request::get('scope')) == 'ALL')
						{{'POSTED'}}
					@else
						{{Request::get('scope')}}
					@endif
				</button>

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
		    <div class="panel-heading">Direct Payment Header
			<a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span> Print </a>
			</div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid" class="table table-striped"></table>
            				<div id="jqGridPager"></div>
        			</div>
		    	</div>
		</div>

		<div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel">
				<b>BANK CODE: </b><span id="bankcode_show"></span><br>
				<b>PAY TO: </b><span id="payto_show"></span>

				<i class="fa fa-angle-double-up" style="font-size:24px;margin: 0 0 0 12px"></i>
				<i class="fa fa-angle-double-down" style="font-size:24px;margin: 0 0 0 12px"></i>
				<div class="pull-right" style="position: absolute; padding: 0 0 0 0; right: 50px; top: 10px;">
					<h5>Direct Payment Detail</h5>
				</div>				
			</div>
			<div id="jqGrid3_panel" class="panel-collapse collapse">
				<div class="panel-body">
	    			<div id="" class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid3" class="table table-striped"></table>
            			<div id="jqGridPager3"></div>
    				</div>
	    		</div>
			</div>	
		</div>

</div>

	<!-- ***************End Search + table ********************* -->
		<div id="dialogForm" title="Add Form" >
			<form class='form-horizontal' style='width:100%' id='formdata'>
				{{ csrf_field() }}
				<div class='col-md-12'>
					<div class='panel panel-info'>
						<div id="detail" class="panel-heading">Bank In Registration Header</div>
							<div class="panel-body">

								<input id="source" name="source" type="hidden">
								<input id="trantype" name="trantype" type="hidden">
								<input id="idno" name="idno" type="hidden">

				    		<div class="form-group">
				    			<label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
				  				<div class="col-md-3">
						 				<div class='input-group'>
											<input id="bankcode" name="bankcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  				</div>
					 					<span id='bc' class="help-block"></span>
                  </div>

					  			<label class="col-md-1 control-label" for="auditno">Auditno</label>  
				  				<div class="col-md-2">
										<input id="auditno" name="auditno" type="text" class="form-control input-sm" readonly rdOnly>
				  				</div>

					  			<label class="col-md-2 control-label" for="amount">Cash Amount</label>  
				  				<div class="col-md-2">
										<input id="amount" name="amount" type="text" class="form-control input-sm" data-validation="required">
				  				</div>
				    		</div>

				    		<div class="form-group">
				    			<label class="col-md-2 control-label" for="paymode">Pay Type</label>  
				  				<div class="col-md-3">
						 				<select class="form-control input-sm" for="paymode" name="paymode" id="paymode">
										  <option value="CASH">CASH</option>
										  <option value="CARD">CARD</option>
										  <option value="CHEQUE">CHEQUE</option>
										</select>
                  </div>

					  			<label class="col-md-1 control-label" for="postdate">Posted Date</label>  
				  				<div class="col-md-2">
										<input id="postdate" name="postdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="{{Carbon\Carbon::now()->format('Y-m-d')}}">
				  				</div>

					  			<label class="col-md-2 control-label" for="comamt">Commision Amt</label>  
				  				<div class="col-md-2">
										<input id="comamt" name="comamt" type="text" class="form-control input-sm" readonly rdOnly>
				  				</div>
				    		</div>

				    		<div class="form-group">
				    			<label class="col-md-2 control-label" for="remarks">Reference</label>  
				  				<div class="col-md-6" >
										<input id="remarks" name="remarks" type="text" class="form-control input-sm">
				  				</div>

					  			<label class="col-md-2 control-label" for="gst">GST</label>  
				  				<div class="col-md-2">
										<input id="gst" name="gst" type="text" class="form-control input-sm" readonly rdOnly>
				  				</div>
				    		</div>

				    		<div class="form-group">
				  				<div id="payer1_div">
					    			<label class="col-md-2 control-label" for="payer1">Payer</label>  
					  				<div class="col-md-6" >
											<input id="payer1" name="payer1" type="text" class="form-control input-sm">
					  				</div>
					  			</div>

				  				<div id="payer2_div" style="display:none">
					    			<label class="col-md-2 control-label" for="payer2">Payer</label>  
					  				<div class="col-md-6" >
							 				<div class='input-group'>
												<input id="payer2" name="payer2" type="text" class="form-control input-sm text-uppercase">
												<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						  				</div>
						 					<span class="help-block"></span>
					  				</div>
					  			</div>

					  			<label class="col-md-2 control-label" for="netamount">Nett Amount</label>  
				  				<div class="col-md-2">
										<input id="netamount" name="netamount" type="text" class="form-control input-sm" readonly rdOnly>
				  				</div>
				    		</div>

				    		<div class="form-group">
					  			<label class="col-md-2 control-label" for="unit">Units</label>  
				  				<div class="col-md-3" >
						 				<select class="form-control input-sm" for="unit" name="unit" id="unit">
										  	<option value="ALL" selected>ALL</option>
						 					@foreach($unit as $unit_obj)
										  	<option value="{{$unit_obj->sectorcode}}">{{$unit_obj->description}}</option>
						 					@endforeach
										</select>
                  </div>

					  			<label class="col-md-offset-3 col-md-2 control-label" for="dtlamt">Total Detail Amt</label>  
				  				<div class="col-md-2">
										<input id="dtlamt" name="dtlamt" type="text" class="form-control input-sm" readonly rdOnly>
				  				</div>
				    		</div>

							</div>
					</div>
				</div>
			</form>

			<div class='col-md-12'>
				<div class='panel panel-info'>
					<div class="panel-heading" style="padding: 15px;">Bank In Registration Detail
						<input id="alloText" placeholder="Search Here.." type="text" class="form-control input-sm" style="position: absolute;
						  right: 120px;
						  top: 10px;
						  width: 200px;">
						<select class="form-control" id="alloCol" style="position: absolute;
					    right: 330px;
					    top: 10px;
					    width: 200px;">
							<option value="trantype" >Type</option>
							<option value="auditno" >Audit No</option>
							<option value="recptno" selected>Document</option>
							<option value="posteddate" >Document Date</option>
						</select>
						<button id="searhcAlloBtn" type="button" class="btn btn-primary" style="position: absolute;
					    right: 50px;
					    top: 10px;">
					  Search</button>
					</div>
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
	<script src="js/finance/CM/bankInRegistration/bankInRegistration.js"></script>
@endsection