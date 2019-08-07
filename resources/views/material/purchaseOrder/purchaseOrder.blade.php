@extends('layouts.main')

@section('title', 'Purchase Order')

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

	<input id="deptcode" name="deptcode" type="hidden" value="{{Session::get('deptcode')}}">
	<input id="deldept" name="deldept" type="hidden" value="{{Session::get('deldept')}}">
	<input id="scope" name="scope" type="hidden" value="{{Request::get('scope')}}">
	<input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">

	<!--***************************** Search + table ******************-->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%; position:relative'>
			<fieldset>

			<input id="getYear" name="getYear" type="hidden"  value="<?php echo date("Y") ?>">

				<div class='col-md-12' style="padding:0 0 5px 0;">
					<div class="form-group"> 
					  <div class="col-md-2">
					  	<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' name='Scol' class="form-control input-sm"></select>
		              </div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
								<input  name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">

							<div  id="tunjukname" style="display:none">
								<div class='input-group'>
									<input id="supplierkatdepan" name="supplierkatdepan" type="text" maxlength="12" class="form-control input-sm">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								</div>
								<span class="help-block"></span>
							</div>
							
						</div>
		             </div>
				</div>

				<div class="col-md-2">
				  	<label class="control-label" for="Status">Status :</label>  
					  	<select id="Status" name="Status" class="form-control input-sm">
					      <option value="All" selected>ALL</option>
					      <option value="Open">OPEN</option>
					      <option value="Confirmed">CONFIRMED</option>
					      <option value="Issued">ISSUED</option>
					      <option value="Cancelled">CANCELLED</option>
					    </select>
	            </div>

			  	<div class="col-md-2">
			  		<label class="control-label" for="trandept">Purchase Department :</label> 
						<select id='trandept' class="form-control input-sm">
				      		<option value="All" selected>ALL</option>
						</select>
				</div>

				<div id="div_for_but_post" class="col-md-6 col-md-offset-2" style="padding-top: 20px; text-align: end;">
					<span id="error_infront" style="color: red"></span>
					<button type="button" class="btn btn-primary btn-sm" id="but_reopen_jq" data-oper="reopen" style="display: none;">REOPEN</button>
					<button type="button" class="btn btn-primary btn-sm" id="but_post_jq" data-oper="posted" style="display: none;">ISSUED</button>
					<button type="button" class="btn btn-default btn-sm" id="but_soft_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
				</div>

			 </fieldset> 
		</form>

    	<div class="panel panel-default">
		    	<div class="panel-heading">Purchase Order DataEntry Header</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>

        
        	<div class='click_row'>
        		<label class="control-label">Purchase Order No</label>
        		<span id="ponodepan" style="display: block;">&nbsp</span>
        	</div>
        	<div class='click_row'>
				<label class="control-label">Purchase Dept</label>
        		<span id="prdeptdepan" style="display: block;">&nbsp</span>
        	</div>
	   

         <div class="panel panel-default" id="jqGrid3_c">
		    	<div class="panel-heading clearfix collapsed" data-toggle="collapse" href="#jqGrid3_panel"><i class="fa fa-angle-double-up" style="font-size:24px"></i>
    			<i class="fa fa-angle-double-down" style="font-size:24px"></i>Purchase Order DataEntry Detail  </div>
		    		<div id="jqGrid3_panel" class="panel-collapse collapse">
			    		<div class="panel-body">
			    			<div class='col-md-12' style="padding:0 0 15px 0">
		            			<table id="jqGrid3" class="table table-striped"></table>
		            			<div id="jqGridPager3"></div>
		    				</div>
			    		</div>
			    	</div>		
		</div>
    </div>
	<!-------------------------------- End Search + table ------------------>
		
		<div id="dialogForm" title="Add Form" >
			<div class='panel panel-info'>
				<div class="panel-heading">
					Purchase Order Header
					<a class='pull-right pointer text-primary' id='pdfgen1'><span class='fa fa-print'></span> Print </a>
				</div>
					<div class="panel-body" style="position: relative;">
						<form class='form-horizontal' style='width:99%' id='formdata'>
						{{ csrf_field() }}
							<input id="purordhd_idno" name="purordhd_idno" type="hidden">
							<input id="referral" name="referral" type="hidden">
							<input id="purordhd_delordno" name="purordhd_delordno" type="hidden">

							<div class="form-group">
								<label class="col-md-2 control-label" for="purordhd_prdept">Purchase Department</label>
									<div class="col-md-2">
									  <div class='input-group'>
										<input id="purordhd_prdept" name="purordhd_prdept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  	</div>
                                 <label class="col-md-2 control-label" for="purordhd_purordno">PO No</label>  
						  			<div class="col-md-2"> 
						  			<input id="purordhd_purordno" name="purordhd_purordno" type="text" class="form-control input-sm" frozeOnEdit hideOne rdonly>
                                     </div>
                             
                                <label class="col-md-2  control-label" for="purordhd_recno">Record No</label>  
						  			<div class="col-md-2">
										<input id="purordhd_recno" name="purordhd_recno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						  			</div>
						  		
							</div>

							<div class="form-group">
                            	<label class="col-md-2 control-label" for="purordhd_deldept">Delivery Department</label>	 
								<div class="col-md-2">
								  <div class='input-group'>
									<input id="purordhd_deldept" name="purordhd_deldept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								  </div>
								  <span class="help-block"></span>
								</div>

						        <label class="col-md-2 control-label" for="purordhd_reqdept">Req Dept</label>	 
							    <div class="col-md-2">
								  <div class='input-group'>
									<input id="purordhd_reqdept" name="purordhd_reqdept" type="text" maxlength="12" class="form-control input-sm" >
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								  </div>
							    </div>

                                <label class="col-md-2 control-label" for="purordhd_purreqno">Req No</label>	 
							 	<div class="col-md-2">
								  <div class='input-group'>
									<input id="purordhd_purreqno" name="purordhd_purreqno" type="text" maxlength="12" class="form-control input-sm" >
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
								  </div>
							  	</div>
							</div>

							<div class="form-group">
                              
                              <label class="col-md-2 control-label" for="purordhd_suppcode">Supplier Code</label>	 
								 <div class="col-md-2">
									  <div class='input-group'>
										<input id="purordhd_suppcode" name="purordhd_suppcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

                                  <label class="col-md-2 control-label" for="credcode">Creditor</label>	  
								  <div class="col-md-2">
									  <div class='input-group'>
										<input id="purordhd_credcode" name="purordhd_credcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>
								  
							</div>
							<hr/>

                            <div class="form-group">
                           		<label class="col-md-2 control-label" for="purordhd_purdate">PO Date</label>  
						  		<div class="col-md-2">
						  			 <input id="purordhd_purdate" name="purordhd_purdate" type="date" value="<?php echo date("Y-m-d"); ?>" maxlength="10" class="form-control input-sm" min="<?php $backday= 3; $date =  date('Y-m-d', strtotime("-$backday days")); echo $date;?>" max="<?php echo date('Y-m-d');?>" data-validation="required"> 
								
						  		</div>
                             
                                <label class="col-md-2 control-label" for="purordhd_expecteddate">Expected Date</label>  
						  		<div class="col-md-2">
									<input id="purordhd_expecteddate" name="purordhd_expecteddate" type="date" maxlength="10" class="form-control input-sm" data-validation="required"  value="<?php echo date("Y-m-d"); ?>" min="<?php echo date("Y-m-d"); ?>">
						  		</div>

						  		<label class="col-md-2 control-label" for="termdays">Payment Terms</label>  
						  		<div class="col-md-2"> 
						  			<input id="purordhd_termdays" name="purordhd_termdays" type="text" class="form-control input-sm" data-validation="number" frozeOnEdit hideOne value="30">
						  		</div>
                            </div>

                            <hr/>

                            <div class="form-group">
								<label class="col-md-2 control-label" for="purordhd_perdisc">Discount[%]</label>  
					  			<div class="col-md-2">
									<input id="purordhd_perdisc" name="purordhd_perdisc" type="text" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
					  			</div>

						  		<label class="col-md-2 control-label" for="purordhd_amtdisc">Amount Discount</label>	  
						  		<div class="col-md-2">
									<input id="purordhd_amtdisc" name="purordhd_amtdisc" type="text" maxlength="12" class="form-control input-sm" data-sanitize="numberFormat" data-sanitize-number-format="0,0.00">
					  			</div>
								
								<label class="col-md-2 control-label" for="purordhd_recstatus">Record Status</label>  
							    <div class="col-md-2">
								  <input id="purordhd_recstatus" name="purordhd_recstatus" type="text" class="form-control input-sm" rdonly>
							    </div>
                           
                            </div>
                             
                             <div class="form-group">
                             	<label class="col-md-2 control-label" for="purordhd_subamount">Sub Amount</label>  
					  			<div class="col-md-2">
									<input id="purordhd_subamount" name="purordhd_subamount" type="text" maxlength="12" class="form-control input-sm" rdonly>
					  			</div>

					  			<label class="col-md-2 control-label" for="purordhd_totamount">Total Amount</label>  
					  			<div class="col-md-2">
									<input id="purordhd_totamount" name="purordhd_totamount" type="text" maxlength="12" class="form-control input-sm" rdonly>
					  			</div>


						  		<label class="col-md-2 control-label" for="purordhd_taxclaimable">Tax Claim</label>  
								  <div class="col-md-2">
									<label class="radio-inline"><input type="radio" name="purordhd_taxclaimable" data-validation="required" value='Claimable'>Yes</label><br>
									<label class="radio-inline"><input type="radio" name="purordhd_taxclaimable" data-validation="required"  value='Non-Claimable'>No</label>
								  </div> 

							   <div class="form-group">
								<label class="col-md-2 control-label" for="purordhd_remarks">Remark</label>   
						  			<div class="col-md-5">
						  				<textarea rows="5" id='purordhd_remarks' name='purordhd_remarks' class="form-control input-sm" ></textarea>
						  			</div>
					    		</div>

                            </div>


					    	<div class="form-group data_info">
						    	<div class="col-md-6 minuspad-13">
									<label class="control-label" for="purordhd_upduser">Last Entered By</label>  
						  			<input id="purordhd_upduser" name="purordhd_upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="purordhd_upddate">Last Entered Date</label>
						  			<input id="purordhd_upddate" name="purordhd_upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
					    		<div class="col-md-6 minuspad-13">
									<label class="control-label" for="purordhd_authpersonid">Authorized By</label>  
						  			<input id="purordhd_authpersonid" name="purordhd_authpersonid" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>

					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="purordhd_authdate">Authorized Date</label>
						  			<input id="purordhd_authdate" name="purordhd_authdate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
							</div>
					</form>
				</div>
			</div>

			<div class='panel panel-info'>
				<div class="panel-heading">Purchase Order Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<!-- <input id="gstpercent" name="gstpercent" type="hidden">
							<input id="convfactor_uom" name="convfactor_uom" type="hidden" value='1'>
							<input id="convfactor_pouom" name="convfactor_pouom" type="hidden" value='1'> -->
							<input type="hidden" id="jqgrid2_itemcode_refresh" name="" value="0">

							<div id="jqGrid2_c" class='col-md-12'>
								<table id="jqGrid2" class="table table-striped"></table>
					            <div id="jqGridPager2"></div>
							</div>
						</form>
					</div>

					<div class="panel-body">
						<div class="noti"><ol></ol>
						</div>
					</div>
			</div>
				
			<div id="dialog_remarks" title="Remarks">
			  <div class="panel panel-default">
			    <div class="panel-body">
			    	<textarea id='remarks2' name='remarks2' rows='6' class="form-control input-sm" style="width:100%;"></textarea>
			    </div>
			  </div>
			</div>
		</div>

			
@endsection


@section('scripts')

	<script src="js/material/purchaseOrder/purchaseOrder.js"></script>
	<script src="js/material/purchaseOrder/pdfgen.js"></script>
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
	
@endsection