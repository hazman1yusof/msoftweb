@extends('layouts.main')

@section('title', 'Direct Payment')

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

<!--***************************** Search + table ******************-->
	 
<div class='row'>
	<form id="searchForm" class="formclass" style='width:99%; position:relative'>
		<fieldset>
			<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
						<div class="col-md-2">
						  	<label class="control-label" for="Scol">Search By : </label>  
						  		<select id='Scol' name='Scol' class="form-control input-sm"></select>
			            </div>

						<div class="col-md-5">
						  	<label class="control-label"></label>  
							<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
						</div>

			        </div>
				</div>

				<div id="div_for_but_post" class="col-md-10 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<button type="button" class="btn btn-primary btn-sm" id="but_post_jq" data-oper="posted" style="display: none;">POST</button>
					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
				</div>
		</fieldset> 
	</form>

		<div class="panel panel-default">
		    <div class="panel-heading">Direct Payment Header</div>
		    	<div class="panel-body">
		    		<div class='col-md-12' style="padding:0 0 15px 0">
            			<table id="jqGrid" class="table table-striped"></table>
            				<div id="jqGridPager"></div>
        			</div>
		    	</div>
		</div>

		<div class="panel panel-default" style="position: relative;" id="jqGrid3_c">
			<div class="panel-heading clearfix collapsed" data-toggle="collapse" data-target="#jqGrid3_panel">
				<b>Bank Code: <span id="bankcode_show"></span></b><br>
				Pay To: <span id="payto_show"></span>

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
						<div id="detail" class="panel-heading">Direct Payment Header</div>
							<div class="panel-body">

								<input id="source" name="source" type="hidden" value="CM">
								<input id="trantype" name="trantype" value = "DP" type="hidden">
								<input id="auditno" name="auditno" type="hidden">
								<input id="idno" name="idno" type="hidden">


								<div class="form-group" style="position: relative">
						  			<label class="col-md-2 control-label" for="auditno">Audit No</label>  
						  				<div class="col-md-3"> <!--- value="<?php// echo "auditno";?>" -->
						  					<input id="auditno" name="auditno" type="text" class="form-control input-sm" frozeOnEdit rdonly>
						  				</div>

						  			<label class="col-md-2 control-label" for="pvno">PV No</label>  
						  				<div class="col-md-3">
											<input id="pvno" name="pvno" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit rdonly>
						  				</div>
						  				<div id="recstatus"></div>
								</div>

								<div class="form-group">
						  			<label class="col-md-2 control-label" for="actdate">Payment Date</label>  
						  				<div class="col-md-3">
											<input id="actdate" name="actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>">
						  				</div>

						  			<label class="col-md-2 control-label" for="paymode">Payment Mode</label>  
						  				<div class="col-md-3">
							 				<div class='input-group'>
												<input id="paymode" name="paymode" type="text" class="form-control input-sm text-uppercase" data-validation="required" >
												<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  				</div>
							 				<span class="help-block"></span>
		                      			</div>
					    		</div>

					    		<div class="form-group">
					    			<label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
						  				<div class="col-md-3" id="bankcode_parent">
							 				<div class='input-group'>
												<input id="bankcode" name="bankcode" type="text" class="form-control input-sm text-uppercase" data-validation="required">
												<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
							  				</div>
							 				<span id='bc' class="help-block"></span>
		                      			</div>

		                    		<label class="col-md-2 control-label" for="cheqno">Cheque No</label>  
						  				<div class="col-md-3" id="cheqno_parent">
							 				<div class='input-group'>
												<input id="cheqno" name="cheqno" type="text" class="form-control input-sm">
													<a class='input-group-addon btn btn-primary' id="cheqno_a"><span class='fa fa-ellipsis-h' ></span></a>
							  				</div>
							 				<span id='cn' class="help-block"></span>
		                      			</div>
					    		</div>

							    <div class="form-group">
							    	<label class="col-md-2 control-label" for="cheqdate">Cheque Date</label>  
									  	<div class="col-md-3">
											<input id="cheqdate" name="cheqdate" type="date"  maxlength="12" class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>">
									  	</div>

									 <label class="col-md-2 control-label" for="amount">Amount</label>  
									  	<div class="col-md-2">
												<input id="amount" name="amount" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00" value="0.00" rdonly>  <!--data-validation-allowing="float" -->
						 				</div>
							    </div>

							    <div class="form-group">
							    	<label class="col-md-2 control-label" for="payto">Pay To</label>  
								  		<div class="col-md-3">
									 		<div class='input-group'>
												<input id="payto" name="payto" type="text" class="form-control input-sm text-uppercase" data-validation="required"><!---->
													<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  		</div>
									 		<span class="help-block"></span>
				                      	</div>
				                      	
							        
									<label class="col-md-2 control-label" for="TaxClaimable">GST</label>  
									  <div class="col-md-3">
										<label class="radio-inline"><input type="radio" data-validation="required" name="TaxClaimable" value='Claimable'>Claimable</label><br>
										<label class="radio-inline"><input type="radio" data-validation="required" name="TaxClaimable" value='Non-Claimable'>Non-Claimable</label>
									  </div>
							    </div>

							    <div class="form-group">
							    	<label class="col-md-2 control-label" for="remarks">Remarks</label> 
							    		<div class="col-md-8"> 
							    		<textarea class="form-control input-sm text-uppercase" name="remarks" rows="2" cols="55" maxlength="400" id="remarks" ></textarea>
							    		</div>
							    </div>
							</div>
					</div>
				</div>
			</form>

			<div class='col-md-12'>
				<div class='panel panel-info'>
					<div class="panel-heading">Direct Payment Detail</div>
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
	<script src="js/finance/CM/directPayment/directPayment.js"></script>
@endsection