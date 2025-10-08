@extends('layouts.main')

@section('title', 'Receipt Setup')

@section('style')
	#gridAllo_c input[type='text'][rowid]{
		height: 30%;
		padding: 4px 12px 4px 12px;
	}
	#alloText{width:9%;}#alloText{width:60%;}#alloCol{width: 30%;}
	#alloCol, #alloText{
		display: inline-block;
		height: 70%;
		padding: 4px 12px 4px 12px;
	}
	#alloSearch{
		border-style: solid;
		border-width: 0px 1px 1px 1px;
		padding-top: 5px;
		padding-bottom: 5px;
		border-radius: 0px 0px 5px 5px;
		background-color: #f8f8f8;
		border-color: #e7e7e7;
	}
@endsection('style')

@section('body')

<!-------------------------------- Search + table ---------------------->
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

					  	<div class="col-md-5" style="margin-top: 4px;">
					  		<label class="control-label"></label>  
								<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase" tabindex="2">

								<div id="payer_text" style="display:none">
									<div class='input-group'>
										<input id="payer_search" name="payer_search" type="text" maxlength="12" class="form-control input-sm">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									</div>
									<span id="payer_search_hb" class="help-block"></span>
								</div>

								<div id="actdate_text" class="form-inline" style="display:none">
									FROM DATE <input id="docudate_from" type="date" placeholder="FROM DATE" class="form-control text-uppercase">
									TO DATE <input id="docudate_to" type="date" placeholder="TO DATE" class="form-control text-uppercase" >
									<button type="button" class="btn btn-primary btn-sm" id="docudate_search">SEARCH</button>
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

		<div class="StextClass">
			<div class=Stext2 id='allocate' style="padding:0px 0px 0px 15px;right: 100px;">
				<a href="#" class="btn-sm allobtn" role="button">Allocate</a>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading">Receipt Header
				<a class='pull-right pointer text-primary' id='pdfgen1' href="" target="_blank"><span class='fa fa-print'></span> Print </a>
			</div>
			<div class="panel-body">
				<div class='col-md-12' style="padding:0 0 15px 0">
					<table id="jqGrid" class="table table-striped"></table>
					<div id="jqGridPager"></div>
				</div>
			</div>
		</div> 
		
</div>
<!-- <div class='row'>
	<form id="searchForm" class="formclass" style='width:99%'>
		<fieldset>
			<div class="ScolClass" style="padding:0 0 0 15px">
				<div name='Scol'>Search By : </div>
			</div>
			<div class="StextClass">
				<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				<div class=Stext2 id='allocate'>
					<a href="#" class="btn-sm allobtn" role="button">Allocate</a>
				</div>
			</div>
		 </fieldset> 
	</form>
	
	  <div class="panel panel-default">
		<div class="panel-body">
			<div class='col-md-12' style="padding:0 0 15px 0">
				<table id="jqGrid" class="table table-striped"></table>
					<div id="jqGridPager"></div>
			</div>
		</div>
	</div>
</div> -->
<!-------------------------------- End Search + table ------------------>

<!-------------------------------- Start Add + Login ------------------>

<div id="LoginDiv" hidden>
  <form class='form-horizontal' style='width:99%' id="LoginForm" >LOGIN
    <!-- <div class="col-sm-12">
      <input placeholder="Example placeholder 1" id="ex1" />
    </div> -->
	<br><br><br>
	<div class="form-group">
        <label class="col-md-2 control-label" for="till_tillcode">Till Code</label>  
        <div class="col-md-6">
            <div class='input-group'>
                <input id="till_tillcode" name="till_tillcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
                <a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
            </div>
        </div>
	</div>

	<div class="form-group">
        <label class="col-md-2 control-label" for="description">Description</label>  
        <div class="col-md-6">
            <input id="description" name="description" type="text" class="form-control input-sm" rdonly>
        </div>				  
    </div>

	<div class="form-group">
		<label class="col-md-2 control-label" for="till_dept">Department</label>  
		<div class="col-md-6">
			<div class='input-group'>
				<input id="till_dept" name="till_dept" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
				<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
			</div>
		</div>				  
	</div>

	<div class="form-group">
		<label class="col-md-2 control-label" for="tillstatus">Till Status</label>  
		<div class="col-md-4">
			<input id="tillstatus" name="tillstatus" type="text" class="form-control input-sm" value="C">
		</div>
	</div>
        
	<div class="myformgroup">
		<label class="control-label col-md-2" for="defopenamt">Opening Amount</label> 
		<div class='col-md-4'> 
			<input id="defopenamt" name="defopenamt" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
		</div>
	</div>
  </form>
</div>

<!-------------------------------- End Add + Login ------------------>

<div id="dialogForm" title="Add Form" >
	<form style='width:99%' id='formdata' autocomplete="off">
		<input type='hidden' name='dbacthdr_source' value='PB'>
		<input type='hidden' name='dbacthdr_tillno' >
		<input type='hidden' name='dbacthdr_tillcode' >
		<input type='hidden' name='dbacthdr_hdrtype'>
		<input type='hidden' name='dbacthdr_paytype' id='dbacthdr_paytype'>
		<input type='hidden' name='dbacthdr_auditno'>
		<input type='hidden' name='updpayername'> 
		<input type='hidden' name='updepisode'>
		<input type='hidden' name='dbacthdr_lineno_' value='1'> 
		<input type='hidden' name='dbacthdr_epistype'>
		<input type='hidden' name='dbacthdr_billdebtor'>
		<input type='hidden' name='dbacthdr_debtorcode'>
		<input type='hidden' name='dbacthdr_lastrcnumber'>
		<input type='hidden' name='dbacthdr_drcostcode'>
		<input type='hidden' name='dbacthdr_crcostcode'>
		<input type='hidden' name='dbacthdr_dracc'>
		<input type='hidden' name='dbacthdr_cracc'>
		<input type='hidden' name='dbacthdr_idno'>
		<input type='hidden' name='dbacthdr_currency' value='RM'>
		<input type='hidden' name='postdate'>
		<input type='hidden' name='dbacthdr_RCOSbalance'>
		<input type='hidden' name='dbacthdr_units'>
		<input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">
		
		<div class='col-md-6'>
			<div class='panel panel-info'>
				<div class="panel-heading">Select either Receipt or Deposit</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="radio-inline"><input type="radio" name="optradio" value='receipt' checked>Receipt</label>
						<label class="radio-inline"><input type="radio" name="optradio" value='deposit'>Deposit</label>
					</div>
					<div id="sysparam_c" class="form-group">
			            <table id="sysparam" class="table table-striped"></table>
            			<div id="sysparampg"></div>
					</div>
					<!-- <hr> -->
					<div class="form-group">
						<div class='col-md-2 minuspad-15'>
						<label>Trantype: </label><input id="dbacthdr_trantype" name="dbacthdr_trantype" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
						</div>

						<div class='col-md-10 '>
						<label>Description: </label><input id="dbacthdr_PymtDescription" name="dbacthdr_PymtDescription" type="text" class="form-control input-sm" rdonly>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class='col-md-6'>
			<div class='panel panel-info'>
				<div class="panel-heading">Choose Payer</div>
				<div class="panel-body">
		  			<div class="col-md-12 minuspad-15">
							<label class="control-label" for="dbacthdr_payercode">Payer</label>  
			  			<div class='input-group'>
							<input id="dbacthdr_payercode" name="dbacthdr_payercode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value"/>
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
			  			</div>
		  			</div>

		  			<div class="col-md-12 minuspad-15">
						<label class="control-label" for="dbacthdr_payername">Payer Name</label>
			  			<div class=''>
							<input id="dbacthdr_payername" name="dbacthdr_payername" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
						</div>
		  			</div>

					<div class="col-md-6 minuspad-15">
						<label class="control-label" for="dbacthdr_debtortype">Financial Class</label>
			  			<div class=''>
							<input id="dbacthdr_debtortype" name="dbacthdr_debtortype" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
						</div>
			  			<span class="help-block"></span>
		  			</div>
		  			<div class='clearfix'></div>
					<hr>
					<label class="control-label" for="dbacthdr_debtortype">Receipt Number</label>
					<input id="dbacthdr_recptno" name="dbacthdr_recptno" type="text" class="form-control input-sm text-uppercase" rdonly>
					<div id='divMrnEpisode'>
						
						<div class="col-md-8 minuspad-15">
							<label class="control-label" for="dbacthdr_mrn">MRN</label>  
				  			<div class="">
					  			<div class='input-group'>
									<input id="dbacthdr_mrn" name="dbacthdr_mrn" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  			</div>
					  			<span class="help-block"></span>
				  			</div>
				  		</div>

						<div class="col-md-4 minuspad-15" id="dbacthdr_episno_div">
							<label class="control-label" for="dbacthdr_episode">Episode</label>  
							<div class="">
					  			<div class=''>
									<input id="dbacthdr_episno" name="dbacthdr_episno" type="text" class="form-control input-sm" rdonly>
								</div>
					  			<span class="help-block"></span>
				  			</div>
				  	</div>

				  	<div class="col-md-8 minuspad-15">
							<label class="control-label" for="dbacthdr_quoteno">Quote No.</label>  
				  			<div class="">
					  			<div class='input-group'>
									<input id="dbacthdr_quoteno" name="dbacthdr_quoteno" type="text" class="form-control input-sm text-uppercase" >
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  			</div>
					  			<span class="help-block"></span>
				  			</div>
				  	</div>

					</div>
				</div>
			</div>
		</div>

		<div class='col-md-12' style="margin:0px 0px 12px 0px;display:none" id="ND0001_case" >
			<label class="control-label col-md-1" for="dbacthdr_categorydept">Department</label> 
			<div class="col-md-5">
	  			<div class="">
		  			<div class='input-group'>
						<input id="dbacthdr_categorydept" name="dbacthdr_categorydept" type="text" class="form-control input-sm text-uppercase" >
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
		  			</div>
		  			<span class="help-block"></span>
	  			</div>
  		</div>

			<label class="control-label col-md-1" for="dbacthdr_category">Category</label>  
			<div class="col-md-5">
	  			<div class="">
		  			<div class='input-group'>
						<input id="dbacthdr_category" name="dbacthdr_category" type="text" class="form-control input-sm text-uppercase" >
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
		  			</div>
		  			<span class="help-block"></span>
	  			</div>
  		</div>
	  </div>

		<div class='col-md-12'>
			<div class="form-group">
				<label class="control-label col-md-1" for="dbacthdr_remark">Remark</label> 
		  		<div class='col-md-11'> 
					<input id="dbacthdr_remark" name="dbacthdr_remark" type="text" class="form-control input-sm text-uppercase">
				</div>
			</div>
			<!-- <div class='clearfix'></div> -->
			<hr>
		</div>
	</form><!--end formdata-->
		<div class='col-md-12'>
			<div class='panel panel-info'>
				<div class="panel-heading">Choose type of exchange</div>
				<div class="panel-body">
					<ul class="nav nav-tabs">
						<li><a data-toggle="tab" href="#tab-cash" form='#f_tab-cash'>Cash</a></li>
						<li><a data-toggle="tab" href="#tab-card" form='#f_tab-card'>Card</a></li>
						<li><a data-toggle="tab" href="#tab-cheque" form='#f_tab-cheque'>Cheque</a></li>
						<li><a data-toggle="tab" href="#tab-debit" form='#f_tab-debit'>Auto Debit</a></li>
						<li><a data-toggle="tab" href="#tab-forex" form='#f_tab-forex'>Forex</a></li>
					</ul>

					<div class="tab-content">
						<div id="tab-cash" class="tab-pane fade form-horizontal">
							<form id='f_tab-cash' autocomplete="off">
							<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
							<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="hidden" value="CASH">
							</br>
							<div class="myformgroup">
								<label class="control-label col-md-2" for="dbacthdr_amount">Payment</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
								</div>

								<label class="control-label col-md-2" for="dbacthdr_outamount">Outstanding</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2" for="dbacthdr_RCCASHbalance">Cash Balance</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_RCCASHbalance" name="dbacthdr_RCCASHbalance" type="text" class="form-control input-sm" value="0.00">
								</div>

								<label class="control-label col-md-2" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
								</div>
							</div>
							</form>
						</div>
						<div id="tab-card" class="tab-pane fade">
							<form id='f_tab-card' autocomplete="off">
							<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
							</br>
							<div id="g_paymodecard_c" class='col-md-4 minuspad-15'>
								<table id="g_paymodecard" class="table table-striped"></table>
								<div id="pg_paymodecard"></div>
								<hr>
								<div class="form-group">
									<label class="control-label col-md-3" for="dbacthdr_paymode">Paymode: </label> 
							  		<div class='col-md-9'> 
										<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" rdonly  data-validation="required" data-validation-error-msg="Please Enter Value" class="form-control input-sm text-uppercase">
									</div>
								</div>
							</div>
							<!-- <div id="g_paycard_c" class='col-md-4 minuspad-15'>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th scope="col">Pay Mode</th>
											<th scope="col">Description</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" rdonly class="form-control input-sm text-uppercase">
											</td>
											<td>
												<input id="paycard_description" name="paycard_description" type="text" rdonly class="form-control input-sm text-uppercase">
											</td>
										</tr>
									</tbody>
								</table>
							</div> -->
							<div class='col-md-8'>
								<div class="form-group">
							  		<div class='col-md-4'> 
										<label class="control-label" for="dbacthdr_amount">Payment</label> 
										<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="form-group">
							  		<div class='col-md-6'> 
										<label class="control-label" for="dbacthdr_outamount">Outstanding</label> 
										<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
									</div>
							  		<div class='col-md-6'> 
										<label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
										<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
									</div>
								</div>
								<div class="form-group">
							  		<div class='col-md-12'>
										<label class="control-label" for="dbacthdr_reference">Reference</label> 
										<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
									</div>
								</div>
								<div class="form-group">
							  		<div class='col-md-6'>
										<label class="control-label" for="dbacthdr_authno">Authorization No.</label> 
								  		<div class=''> 
											<input id="dbacthdr_authno" name="dbacthdr_authno" type="text" class="form-control input-sm text-uppercase">
										</div>
							  		</div>
							  		<div class='col-md-6'>
										<label class="control-label" for="dbacthdr_expdate">Expiry Date</label> 
								  		<div class=''> 
											<input id="dbacthdr_expdate" name="dbacthdr_expdate" type="month" class="form-control input-sm">
										</div>
							  		</div>
								</div>
							</div>
							</form>
						</div>
						<div id="tab-cheque" class="tab-pane fade form-horizontal">
							<form id='f_tab-cheque' autocomplete="off">
							<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="hidden" value="CHEQUE">
							</br>
							<div class="myformgroup">
								<label class="control-label col-md-2" for="dbacthdr_entrydate">Transaction Date</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
								</div>

								<label class="control-label col-md-2" for="dbacthdr_amount">Payment</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2" for="dbacthdr_outamount">Outstanding</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
								</div>

								<label class="control-label col-md-2" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-2" for="dbacthdr_reference">Reference</label> 
						  		<div class='col-md-8'> 
									<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
								</div>
							</div>
							</form>
						</div>
						<div id="tab-debit" class="tab-pane fade">
							<form id='f_tab-debit' autocomplete="off">
							</br>
							<div id="g_paymodebank_c" class='col-md-4 minuspad-15'>
								<table id="g_paymodebank" class="table table-striped"></table>
								<div id="pg_paymodebank"></div>
								<hr>
								<div class="form-group">
									<label class="control-label col-md-3" for="dbacthdr_paymode">Paymode:</label> 
							  		<div class='col-md-9'> 
										<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value" rdonly>
									</div>
								</div>
							</div>
							<!-- <div id="g_paybank_c" class='col-md-4 minuspad-15'>
								<table class="table table-bordered">
									<thead>
										<tr>
											<th scope="col">Pay Mode</th>
											<th scope="col">Description</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" rdonly class="form-control input-sm text-uppercase">
											</td>
											<td>
												<input id="paybank_description" name="paybank_description" type="text" rdonly class="form-control input-sm text-uppercase">
											</td>
										</tr>
									</tbody>
								</table>
							</div> -->
							<div class='col-md-8'>
								<div class="form-group">
							  		<div class='col-md-4'> 
										<label class="control-label" for="dbacthdr_entrydate">Transaction Date</label> 
										<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">

									</div>
								</div>
								<div class="clearfix"></div>
								<div class="myformgroup">
							  		<div class='col-md-6'> 
										<label class="control-label" for="dbacthdr_bankcharges">Bank Charges</label> 
										<input id="dbacthdr_bankcharges" name="dbacthdr_bankcharges" type="text" class="form-control input-sm" value="0.00">
									</div>
							  		<div class='col-md-6'> 
										<label class="control-label" for="dbacthdr_amount">Payment</label> 
										<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
									</div>
								</div>
								<div class="form-group">
							  		<div class='col-md-6'>
										<label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
										<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
							  		</div>
							  		<div class='col-md-6'>
										<label class="control-label" for="dbacthdr_outamount">Outstanding</label> 
										<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
							  		</div>
								</div>
								<div class="form-group">
							  		<div class='col-md-12'> 
										<label class="control-label" for="dbacthdr_reference">Reference</label> 
										<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm text-uppercase" data-validation="required" data-validation-error-msg="Please Enter Value">
									</div>
								</div>
							</div>
							</form>
						</div>
						<div id="tab-forex" class="tab-pane fade">
							<form id='f_tab-forex' autocomplete="off">
							<input id="dbacthdr_currency" name="dbacthdr_currency" type="hidden">
							<input id="dbacthdr_rate" name="dbacthdr_rate" type="hidden">
							<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="hidden">
							</br>
							<div id="g_forex_c" class='col-md-4 minuspad-15'>
								<table id="g_forex" class="table table-striped"></table>
								<div id="pg_forex"></div>
								<hr>
								<div class="form-group">
									<label class="control-label col-md-3" for="dbacthdr_paymode">Paymode:</label> 
							  		<div class='col-md-9'> 
										<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" class="form-control input-sm text-uppercase" rdonly>
									</div>
								</div>
							</div>
							<div class='col-md-8'>
								<div class="myformgroup">
							  		<div class='col-md-6'>
										<label class="control-label" for="dbacthdr_outamount">Outstanding</label> 
										<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" value="0.00" rdonly>
							  		</div>
							  		<div class='col-md-6'>
										<label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
										<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" value="0.00" rdonly>
							  		</div>
								</div>
								<div class="myformgroup">
									<div class='col-md-4'> 
										<label class="control-label" for="rm">Currency</label> 
										<input id="rm" name="rm" type="text" value='RM' class="form-control input-sm" rdonly>
									</div>
							  		<div class='col-md-8'> 
										<label class="control-label" for="dbacthdr_amount">Amount</label> 
										<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" value="0.00" data-validation="required" data-validation-error-msg="Please Enter Value">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="myformgroup">
									<div class='col-md-4'> 
										<label class="control-label" for="curroth">Currency</label> 
										<input id="curroth" name="curroth" type="text" class="form-control input-sm text-uppercase" rdonly>
									</div>
							  		<div class='col-md-8'>
										<label class="control-label" for="dbacthdr_amount2">Amount</label> 
										<input id="dbacthdr_amount2" name="dbacthdr_amount2" type="text" class="form-control input-sm" value="0.00">
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
</div>

<div id="allocateDialog" title="Create Allocation">
	<form id='formallo'>
		<input id="AlloAuditno" type="hidden" class="form-control input-sm" readonly>
		<div class='col-md-9'>
			<div class="col-md-6">
				<label class="control-label">Document Type</label>
				<input id="AlloDtype" type="text" class="form-control input-sm" readonly>
				<span class="help-block" id="AlloDtype2"></span>
			</div>

			<div class="col-md-6">
				<label class="control-label">Document No.</label>
				<input id="AlloDno" type="text" class="form-control input-sm" readonly>
			</div>

			<div class="col-md-12">
				<!-- <label class="control-label">Debtor</label> -->
				<!-- <input id="AlloDebtor" type="text" class="form-control input-sm" readonly> -->
				<label class="control-label" for="AlloDebtor">Debtor</label>  
	  			<div class='input-group'>
					<input id="AlloDebtor" name="AlloDebtor" type="text" class="form-control input-sm" data-validation="required" data-validation-error-msg="Please Enter Value"/>
					<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
	  			</div>
				<span class="help-block" id="AlloDebtor2"></span>
			</div>

			<div class="col-md-12">
				<label class="control-label">Payer</label>
				<input id="AlloPayer" type="text" class="form-control input-sm" readonly>
				<span class="help-block" id="AlloPayer2"></span>
			</div>

			<div class="col-md-6">
				<label class="control-label">Document Amount</label>
				<input id="AlloAmt" type="text" class="form-control input-sm" readonly>
			</div>

			<div class="col-md-6">
				<label class="control-label">Document O/S</label>
				<input id="AlloOutamt" type="text" class="form-control input-sm" readonly>
			</div>
		</div>

		<div class='col-md-3'>
			
				<div class="col-md-12"><hr>
					<label class="control-label">Balance After Allocate</label>
					<input id="AlloBalance" type="text" class="form-control input-sm" readonly>
				</div>

				<div class="col-md-12">
					<label class="control-label">Total Allocate</label>
					<input id="AlloTotal" type="text" class="form-control input-sm" readonly><hr>
				</div>
		</div>
	</form>

	<div class='col-md-12' id='gridAllo_c' style="padding:0">
		<hr>
        <table id="gridAllo" class="table table-striped"></table>
        <div id="pagerAllo"></div>
    </div>

	<div class="col-md-10 col-md-offset-1" id="alloSearch">
		<label class="control-label" id='alloLabel'>Search</label>
		<input id="alloText" type="text" class="form-control input-sm">
		<select class="form-control" id="alloCol">
			<option value="invno" >Invoice No</option>
			<option value="auditno" >Audit No</option>
			<option value="mrn" >MRN</option>
			<option value="recptno" >Document No</option>
			<option value="newic" >New IC</option>
			<option value="staffid" >Staff ID</option>
			<option value="batchno" >Batch No</option>
		</select>
	</div>
</div>

	<!-- @include('layouts.allocate_part') -->
	@include('layouts.till_part')

@endsection


@section('scripts')
	
	<script src="js/myjs/till_part.js"></script>
	<script src="js/finance/AR/receipt/receipt.js?v=1.6"></script>
	
@endsection