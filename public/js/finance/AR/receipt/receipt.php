<?php 
	include_once('../../../../header.php'); 
?>
<style>
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
</style>
<body style="display:none">

	<div id="tilldet" title="Select Till">
		<form class='form-horizontal' style='width:99%' id='formTillDet' autocomplete="off">
			<div class="form-group">
				<label class="col-md-2 control-label" for="dept">Cashier</label>  
				<div class="col-md-10">
					<input id="cashier" name="cashier" type="text" class="form-control input-sm" readonly="readonly" value="<?php echo $_SESSION['username'];?>">
				</div>
			</div>	
			
			<div class="form-group">
				<label class="col-md-2 control-label" for="effectdate">Till</label>  
				<div class="col-md-10">
				  <div class='input-group'>
					<input id="tilldetTillcode" name="tilldetTillcode" type="text" class="form-control input-sm text-uppercase" data-validation="required" disabled>
					<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
				  </div>
				  <span class="help-block"></span>
				</div>
			</div>
		</form>
	</div>
<!-------------------------------- Search + table ---------------------->
		<form id="searchForm" class="formclass" style='width:99%' autocomplete="off">
			<fieldset>
				<div class="ScolClass">
						<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase col-md-5">
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

    </div>
	<!-------------------------------- End Search + table ------------------>
		
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
		
		<div class='col-md-6'>
			<div class='panel panel-info'>
				<div class="panel-heading">Select either Receipt or deposit</div>
				<div class="panel-body">
					<div class="form-group">
						<label class="radio-inline"><input type="radio" name="optradio" value='receipt' checked>Receipt</label>
						<label class="radio-inline"><input type="radio" name="optradio" value='deposit'>Deposit</label>
					</div>
					<div id="sysparam_c" class="form-group">
			            <table id="sysparam" class="table table-striped"></table>
            			<div id="sysparampg"></div>
					</div>
					<hr>
					<div class="form-group">
						<div class='col-md-2 minuspad-15'>
						<label>Trantype: </label><input id="dbacthdr_trantype" name="dbacthdr_trantype" type="text" class="form-control input-sm" data-validation="required" rdonly>
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
				<div class="panel-heading">Choose Payer code</div>
				<div class="panel-body">
		  			<div class="col-md-12 minuspad-15">
						<label class="control-label" for="dbacthdr_payercode">Payer Code</label>  
			  			<div class='input-group'>
							<input id="dbacthdr_payercode" name="dbacthdr_payercode" type="text" class="form-control input-sm" data-validation="required"/>
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
			  			</div>
		  			</div>

		  			<div class="col-md-12 minuspad-15">
						<label class="control-label" for="dbacthdr_payername">Payer Name</label>
			  			<div class=''>
							<input id="dbacthdr_payername" name="dbacthdr_payername" type="text" class="form-control input-sm" data-validation="required" rdonly>
						</div>
		  			</div>

					<div class="col-md-6 minuspad-15">
						<label class="control-label" for="dbacthdr_debtortype">Fin Class</label>
			  			<div class=''>
							<input id="dbacthdr_debtortype" name="dbacthdr_debtortype" type="text" class="form-control input-sm" data-validation="required" rdonly>
						</div>
			  			<span class="help-block"></span>
		  			</div>
		  			<div class='clearfix'></div>
					<hr>
					<label class="control-label" for="dbacthdr_debtortype">Receipt Number</label>
					<input id="dbacthdr_recptno" name="dbacthdr_recptno" type="text" class="form-control input-sm" rdonly>
					<div id='divMrnEpisode'>
						
						<div class="col-md-8 minuspad-15">
							<label class="control-label" for="dbacthdr_mrn">MRN</label>  
				  			<div class="">
					  			<div class='input-group'>
									<input id="dbacthdr_mrn" name="dbacthdr_mrn" type="text" class="form-control input-sm" data-validation="required" rdonly>
									<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  			</div>
					  			<span class="help-block"></span>
				  			</div>
				  		</div>

						<div class="col-md-4 minuspad-15">
							<label class="control-label" for="dbacthdr_episode">Episode</label>  
							<div class="">
					  			<div class=''>
									<input id="dbacthdr_episno" name="dbacthdr_episno" type="text" class="form-control input-sm" rdonly>
								</div>
					  			<span class="help-block"></span>
				  			</div>
				  		</div>
					</div>
				</div>
			</div>
		</div>
		<div class='col-md-12'>
			<div class="form-group">
				<label class="control-label col-md-1" for="dbacthdr_remark">Remark</label> 
		  		<div class='col-md-11'> 
					<input id="dbacthdr_remark" name="dbacthdr_remark" type="text" class="form-control input-sm">
				</div>
			</div>
			<div class='clearfix'></div>
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
									<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" data-validation="required">
								</div>

								<label class="control-label col-md-2" for="dbacthdr_outamount">Outstanding</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" rdonly>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2" for="dbacthdr_RCCASHbalance">Cash Balance</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_RCCASHbalance" name="dbacthdr_RCCASHbalance" type="text" class="form-control input-sm">
								</div>

								<label class="control-label col-md-2" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" rdonly>
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
										<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" rdonly  data-validation="required" class="form-control input-sm">
									</div>
								</div>
							</div>
							<div class='col-md-8'>
								<div class="form-group">
							  		<div class='col-md-4'> 
										<label class="control-label" for="dbacthdr_amount">Payment</label> 
										<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" data-validation="required">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="form-group">
							  		<div class='col-md-6'> 
										<label class="control-label" for="dbacthdr_outamount">Outstanding</label> 
										<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" rdonly>
									</div>
							  		<div class='col-md-6'> 
										<label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
										<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" rdonly>
									</div>
								</div>
								<div class="form-group">
							  		<div class='col-md-12'>
										<label class="control-label" for="dbacthdr_reference">Reference</label> 
										<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm">
									</div>
								</div>
								<div class="form-group">
							  		<div class='col-md-6'>
										<label class="control-label" for="dbacthdr_authno">Authorization No.</label> 
								  		<div class=''> 
											<input id="dbacthdr_authno" name="dbacthdr_authno" type="text" class="form-control input-sm">
										</div>
							  		</div>
							  		<div class='col-md-6'>
										<label class="control-label" for="dbacthdr_expdate">Expiry Date</label> 
								  		<div class=''> 
											<input id="dbacthdr_expdate" name="dbacthdr_expdate" type="date" class="form-control input-sm">
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
									<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm">
								</div>

								<label class="control-label col-md-2" for="dbacthdr_amount">Payment</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" data-validation="required">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2" for="dbacthdr_outamount">Outstanding</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" rdonly>
								</div>

								<label class="control-label col-md-2" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
						  		<div class='col-md-4'> 
									<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" rdonly>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-md-2" for="dbacthdr_reference">Reference</label> 
						  		<div class='col-md-8'> 
									<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm" data-validation="required">
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
										<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" class="form-control input-sm" data-validation="required" rdonly>
									</div>
								</div>
							</div>
							<div class='col-md-8'>
								<div class="form-group">
							  		<div class='col-md-4'> 
										<label class="control-label" for="dbacthdr_entrydate">Transaction Date</label> 
										<input id="dbacthdr_entrydate" name="dbacthdr_entrydate" type="date" class="form-control input-sm" data-validation="required">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="myformgroup">
							  		<div class='col-md-6'> 
										<label class="control-label" for="dbacthdr_bankcharges">Bank Charges</label> 
										<input id="dbacthdr_bankcharges" name="dbacthdr_bankcharges" type="text" class="form-control input-sm">
									</div>
							  		<div class='col-md-6'> 
										<label class="control-label" for="dbacthdr_amount">Payment</label> 
										<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" data-validation="required">
									</div>
								</div>
								<div class="form-group">
							  		<div class='col-md-6'>
										<label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
										<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" rdonly>
							  		</div>
							  		<div class='col-md-6'>
										<label class="control-label" for="dbacthdr_outamount">Outstanding</label> 
										<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" rdonly>
							  		</div>
								</div>
								<div class="form-group">
							  		<div class='col-md-12'> 
										<label class="control-label" for="dbacthdr_reference">Reference</label> 
										<input id="dbacthdr_reference" name="dbacthdr_reference" type="text" class="form-control input-sm" data-validation="required">
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
										<input id="dbacthdr_paymode" name="dbacthdr_paymode" type="text" class="form-control input-sm" rdonly>
									</div>
								</div>
							</div>
							<div class='col-md-8'>
								<div class="myformgroup">
							  		<div class='col-md-6'>
										<label class="control-label" for="dbacthdr_outamount">Outstanding</label> 
										<input id="dbacthdr_outamount" name="dbacthdr_outamount" type="text" class="form-control input-sm" rdonly>
							  		</div>
							  		<div class='col-md-6'>
										<label class="control-label" for="dbacthdr_RCFinalbalance">Outstanding Balance</label> 
										<input id="dbacthdr_RCFinalbalance" name="dbacthdr_RCFinalbalance" type="text" class="form-control input-sm" rdonly>
							  		</div>
								</div>
								<div class="myformgroup">
									<div class='col-md-4'> 
										<label class="control-label" for="rm">Currency</label> 
										<input id="rm" name="rm" type="text" value='RM' class="form-control input-sm" rdonly>
									</div>
							  		<div class='col-md-8'> 
										<label class="control-label" for="dbacthdr_amount">Amount</label> 
										<input id="dbacthdr_amount" name="dbacthdr_amount" type="text" class="form-control input-sm" data-validation="required">
									</div>
								</div>
								<div class="clearfix"></div>
								<div class="myformgroup">
									<div class='col-md-4'> 
										<label class="control-label" for="curroth">Currency</label> 
										<input id="curroth" name="curroth" type="text" class="form-control input-sm" rdonly>
									</div>
							  		<div class='col-md-8'>
										<label class="control-label" for="dbacthdr_amount2">Amount</label> 
										<input id="dbacthdr_amount2" name="dbacthdr_amount2" type="text" class="form-control input-sm">
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
		<div class='col-md-9'>
			<div class="col-md-6">
				<label class="control-label">Documnet Type</label>
				<input id="AlloDtype" type="text" class="form-control input-sm" readonly>
			</div>

			<div class="col-md-6">
				<label class="control-label">Documnet No.</label>
				<input id="AlloDno" type="text" class="form-control input-sm" readonly>
			</div>

			<div class="col-md-12">
				<label class="control-label">Debtor</label>
				<input id="AlloDebtor" type="text" class="form-control input-sm" readonly>
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
					<label class="control-label">Balance after allocate</label>
					<input id="AlloBalance" type="text" class="form-control input-sm" readonly>
				</div>

				<div class="col-md-12">
					<label class="control-label">Total allocate</label>
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
			<option value="invno" >invoice no</option>
			<option value="auditno" >auditno</option>
			<option value="mrn" >mrn</option>
			<option value="recptno" >docno</option>
			<option value="newic" >newic</option>
			<option value="staffid" >staffid</option>
			<option value="batchno" >batchno</option>
		</select>
	</div>
	<?php 
		include_once('../../../../footer.php'); 
	?>

<script src="receipt.js"></script>
<script src="../../../../assets/js/utility.js"></script>
<script src="../../../../assets/plugins/numeral.min.js"></script>
<script src="../../../../assets/plugins/moment.js"></script>

<script>
		
</script>
</body>
</html>