@extends('layouts.main')

@section('title', 'Inventory Transaction')

@section('body')

	<input id="deptcode" name="deptcode" type="hidden" value="{{Session::get('deptcode')}}">
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
				  	<label class="control-label" for="Status">Status</label>  
					  	<select id="Status" name="Status" class="form-control input-sm">
					      <option value="All" selected>ALL</option>
					      <option value="Open">OPEN</option>
					      <option value="Confirmed">CONFIRMED</option>
					      <option value="Posted">POSTED</option>
					      <option value="Cancelled">CANCELLED</option>
					    </select>
	            </div>

	            <div class="col-md-2">
			  		<label class="control-label" for="trandept">Store / Dept</label> 
						<select id='trandept' class="form-control input-sm">
				      		<option value="All" selected>ALL</option>
						</select>
				</div>

				<div id="div_for_but_post" class="col-md-3 col-md-offset-5" style="padding-top: 20px; text-align: end;">
					<button type="button" class="btn btn-primary btn-sm" id="but_post_jq" data-oper="posted" style="display: none;">POST</button>
					<button type="button" class="btn btn-default btn-sm" id="but_cancel_jq" data-oper="cancel" style="display: none;">CANCEL</button>
				</div>

			 </fieldset> 
		</form>

        <div class="panel panel-default">
		    	<div class="panel-heading">Inventory Data Entry Header</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>

        	<div class='click_row'>
        		<label class="control-label">Dept</label>
        		<span id="txndeptdepan" style="display: block;">&nbsp</span>
        	</div>
        	<div class='click_row'>
				<label class="control-label">Type</label>
        		<span id="trantypedepan" style="display: block;">&nbsp</span>
        	</div>
        	<div class='click_row'>
				<label class="control-label">Document No</label>
        		<span id="docnodepan" style="display: block;">&nbsp</span>
        	</div>

	    <div class="panel panel-default">
		    	<div class="panel-heading">Inventory Data Entry Detail</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
	            			<table id="jqGrid3" class="table table-striped"></table>
	            			<div id="jqGridPager3"></div>
	    				</div>'
		    		</div>
		</div>
        
    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Inventory Transaction Header
					<a class='pull-right pointer text-primary' id='pdfgen1'><span class='fa fa-print'></span> Print </a>
					</div>
				<div class="panel-body" style="position: relative;">
					<form class='form-horizontal' style='width:99%' id='formdata'>
							{{ csrf_field() }}
							<input id="source" name="source" type="hidden">
							<input id="idno" name="idno" type="hidden">
							<input id="crdbfl" name="crdbfl" type="hidden">
							<input id="isstype" name="isstype" type="hidden">

							<div class="form-group">
								<label class="col-md-2 control-label" for="txndept">Transaction Department</label>
									<div class="col-md-3">
									  <div class='input-group'>
										<input id="txndept" name="txndept" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  	</div>

						  		<label class="col-md-2 control-label" for="trantype">Transaction Type</label>
									<div class="col-md-3">
									  <div class='input-group'>
										<input id="trantype" name="trantype" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  	</div>

						  		<div class="form-group">
								<label class="col-md-2 control-label" for="srcdocno">Request RecNo</label>  
						  			<div class="col-md-3" id="srcdocno_parent">
									  	<div class='input-group'>
											<input id="srcdocno" name="srcdocno" type="text" class="form-control input-sm" data-validation="required">
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										</div>
						  			</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="trantype">Transaction Type</label>
									<div class="col-md-3">
									  <div class='input-group'>
										<input id="trantype" name="trantype" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  	</div>

								<label class="col-md-2 control-label" for="sndrcvtype">Receiver Type</label>  
						  			<div class="col-md-3 selectContainer" id="sndrcvtype_parent">
						  				<select id="sndrcvtype" name="sndrcvtype" class="form-control" data-validation="required">
						  				<option value="">Please Select</option>
									      <option value="Department">Department</option>
									      <option value="Supplier">Supplier</option>
									      <option value="Other">Other</option>
									    </select>
						  			</div>

						  		<label class="col-md-2 control-label" for="docno">Document No</label>  
						  			<div class="col-md-2">
										<input id="docno" name="docno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						  			</div>
						  	</div>

						  	<div class="form-group">
								<label class="col-md-2 control-label" for="trandate">Transaction Date</label>  
							  	<div class="col-md-2">
									<input id="trandate" name="trandate" type="date" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date('Y-m-d');?>" class="form-control input-sm" data-validation="required">
							  	</div>


							<label class="col-md-2 control-label" for="sndrcv">Receiver</label>	  
								<div class="col-md-3" id="sndrcv_parent">
									  <div class='input-group'>
										<input id="sndrcv" name="sndrcv" type="text" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

								
								<label class="col-md-2 control-label" for="recno">Record No</label>  
						  			<div class="col-md-2">
										<input id="recno" name="recno" type="text" maxlength="11" class="form-control input-sm" rdonly>
						  			</div>
						  	</div>

						  	<div class="form-group">
								<label class="col-md-2 control-label" for="remarks">Remark</label>   
						  			<div class="col-md-6">
						  				<textarea rows="5" id='remarks' name='remarks' class="form-control input-sm" ></textarea>
						  			</div>

						  		<label class="col-md-2 control-label" for="amount">Amount</label>  
						  			<div class="col-md-2">
										<input id="amount" name="amount" type="text" class="form-control input-sm" value='0.00' rdonly>
						  			</div>
						  		<label class="col-md-3 control-label" for="recstatus">Status</label>  
							  	<div class="col-md-2">
									<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" rdonly>
							  	</div>	
						  				
					    	</div>



						  	<hr/>

						  	

							<div class="form-group data_info">
							<div class="col-md-6 minuspad-13">
									<label class="control-label" for="delordhd_upduser">Last Entered By</label>  
						  			<input id="delordhd_upduser" name="delordhd_upduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="delordhd_upddate">Last Entered Date</label>
						  			<input id="delordhd_upddate" name="delordhd_upddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
					    		<div class="col-md-6 minuspad-13">
									<label class="control-label" for="delordhd_adduser">Check By</label>  
						  			<input id="delordhd_adduser" name="delordhd_adduser" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
					  			<div class="col-md-6 minuspad-13">
									<label class="control-label" for="delordhd_adddate">Check Date</label>
						  			<input id="delordhd_adddate" name="delordhd_adddate" type="text" maxlength="30" class="form-control input-sm" rdonly>
					  			</div>
						    	
							</div>
					</form>
				</div>
			</div>
			
			<div class='panel panel-info'>
				<div class="panel-heading">Inventory Transaction Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-vertical' style='width:99%'>
							<input id="gstpercent" name="gstpercent" type="hidden">
							<input id="convfactor_uom" name="convfactor_uom" type="hidden" value='1'>
							<input id="convfactor_pouom" name="convfactor_pouom" type="hidden" value='1'>

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

	<script src="js/material/goodReturn/goodReturn.js"></script>
	<script src="js/material/goodReturn/pdfgen.js"></script>
	<script src="plugins/pdfmake/pdfmake.min.js"></script>
	<script src="plugins/pdfmake/vfs_fonts.js"></script>
	
@endsection