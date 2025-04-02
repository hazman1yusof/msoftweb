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
	<div class="panel panel-default" style="margin: 10px; height: 50px;">
  	<div class="panel-body">
  	</div>
	</div>

	<div class="panel panel-default" style="margin: 10px; height: 90vh;">
	  <div class="panel-heading">Bank Reconcilation
		<a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span> Print </a>
		</div>
	    	<div class="panel-body">
	    		<div class='col-md-12' style="padding:0 0 15px 0">

	    			 
		  			<div class="col-md-2" >
		  				<label for="bankcode">Payer</label> 
			 				<div class='input-group'>
								<input id="bankcode" name="bankcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
								<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
		  				</div>
		 					<span class="help-block"></span>
	  				</div>
		  			<div class="col-md-4" style="padding-left: 0px;">
		  				<label for="bankname">&nbsp;</label> 
							<input id="bankname" name="bankname" type="text" class="form-control input-sm text-uppercase" data-validation="required">
	  				</div>

      		</div>
	    		<div class='col-md-2' style="padding:0 0 15px 0">
		  			<div class="col-md-12" >
		  				<label for="actdate">Date</label> 
							<input id="actdate" name="actdate" type="date" class="form-control input-sm text-uppercase" data-validation="required">
	  				</div>
		  			<div class="col-md-12" style="padding:15px 15px 5px 15px">
		  				<label for="closeAmtStamnt">Closing Bank Statement</label> 
							<input id="closeAmtStamnt" name="closeAmtStamnt" type="text" class="form-control input-sm text-uppercase" data-validation="required">
	  				</div>
		  			<div class="col-md-12" style="padding:15px 15px 5px 15px">
		  				<label for="cashBkBal">Cash Book Balance</label> 
							<input id="cashBkBal" name="cashBkBal" type="text" class="form-control input-sm text-uppercase" data-validation="required">
	  				</div>
		  			<div class="col-md-12" style="padding:15px 15px 5px 15px">
		  				<label for="unReconAmt">Unreconciled Amount</label> 
							<input id="unReconAmt" name="unReconAmt" type="text" class="form-control input-sm text-uppercase" data-validation="required">
	  				</div>
      		</div>
	    		<div class='col-md-10' style="padding:0 0 15px 0">
      			<table id="jqGrid" class="table table-striped"></table>
      			<div id="jqGridPager"></div>
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

					  			<label class="col-md-2 control-label" for="amount" id="amount_label">Cash Amount</label>  
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

					  			<label class="col-md-2 control-label" for="commamt">Commision Amt</label>  
				  				<div class="col-md-2">
										<input id="commamt" name="commamt" type="text" class="form-control input-sm" disabled>
				  				</div>
				    		</div>

				    		<div class="form-group">
				    			<label class="col-md-2 control-label" for="reference">Reference</label>  
				  				<div class="col-md-6" >
										<input id="reference" name="reference" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value">
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
												<input id="payer2" name="payer2" type="text" class="form-control input-sm text-uppercase" data-validation="required">
												<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						  				</div>
						 					<span class="help-block"></span>
					  				</div>
					  			</div>

					  			<!-- <label class="col-md-2 control-label" for="netamount">Nett Amount</label>  
				  				<div class="col-md-2">
										<input id="netamount" name="netamount" type="text" class="form-control input-sm" readonly rdOnly>
				  				</div> -->
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
						<div id="allo_search_div" style="display:none;">
							<input id="alloDate" type="date" class="form-control input-sm" style="position: absolute;
							  right: 100px;
							  top: 10px;
							  width: 200px;display: none;">
							<input id="alloText" placeholder="Search Here.." type="text" class="form-control input-sm" style="position: absolute;
							  right: 100px;
							  top: 10px;
							  width: 200px;">
							<select class="form-control" id="alloCol" style="position: absolute;
						    right: 310px;
						    top: 10px;
						    width: 200px;">
								<option value="trantype" >Type</option>
								<option value="auditno" >Audit No</option>
								<option value="recptno" selected>Document</option>
								<option value="posteddate" >Document Date</option>
								<option value="reference" >Reference</option>
							</select>
							<button id="resetAlloBtn" type="button" class="btn btn-danger" style="position: absolute;
						    right: 30px;
						    top: 10px;">
						  Reset</button>
							<!-- <button id="searhcAlloBtn" type="button" class="btn btn-primary" style="position: absolute;
						    right: 50px;
						    top: 10px;">
						  Search</button> -->
						</div>
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