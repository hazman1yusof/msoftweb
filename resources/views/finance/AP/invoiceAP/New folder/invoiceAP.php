<?php 
	include_once('../../../../header.php'); 
?>




<body>

	 
	<!--***************************** Search + table ******************-->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<!--<div class="ScolClass">
						<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
				</div>-->

				<div class='col-md-12' style="padding:0 0 15px 0;">
					<div class="form-group"> 
					  <div class="col-md-2">
					  	<label class="control-label" for="Scol">Search By : </label>  
					  		<select id='Scol' class="form-control input-sm"></select>
		              </div>

					  	<div class="col-md-5">
					  		<label class="control-label"></label>  
								<input id="searchText" name="searchText" type="text" class="form-control input-sm" autocomplete="off"/>
						</div>
		             </div>
				</div>

			 </fieldset> 
		</form>
		
		

        <button type="button" id='cancelBut' class='btn btn-info btn-sm pull-right' style='margin: 0.2%'>Cancel</button>
		<button type="button" id='postedBut' class='btn btn-info btn-sm pull-right' style='margin: 0.2%'>Post</button>

		<div class="panel panel-default">
		    	<div class="panel-heading">Invoice AP Header</div>
		    		<div class="panel-body">
		    			<div class='col-md-12' style="padding:0 0 15px 0">
            				<table id="jqGrid" class="table table-striped"></table>
            					<div id="jqGridPager"></div>
        				</div>
		    		</div>
		</div>
		
    	<div class='col-md-12' style="padding:0 0 15px 0">
            <table id="jqGrid" class="table table-striped"></table>
            <div id="jqGridPager"></div>
        </div>

         <div class='col-md-12' style="padding:0 0 15px 0">
	            <table id="jqGrid3" class="table table-striped"></table>
	            <div id="jqGridPager3"></div>
	    </div>
        
    </div>
	<!-- ***************End Search + table ********************* -->

	<div id="dialogForm" title="Add Form" >
		<div class='panel panel-info'>
			<div class="panel-heading">Invoice AP Header</div>
				<div class="panel-body">

					<form class='form-horizontal' style='width:99%' id='formdata'>

						<div class="prevnext btn-group pull-right">
						</div>
								<input id="apacthdr_source" name="apacthdr_source" type="hidden" value="AP">
								<input id="apacthdr_trantype" name="apacthdr_trantype" type="hidden">

						<div class="form-group">

							<label class="col-md-2 control-label" for="apacthdr_ttype">Doc Type</label> 
							<div class="col-md-3" id="apacthdr_ttype">
						 
							  	<select id="apacthdr_ttype" name=apacthdr_ttype" class="form-control" data-validation="required">
						  											     
							       
							       <option value="IN">Supplier</option>
							       <option value="IN">Others</option>
							       <option value="DN">Debit Note</option>
							    </select>
		
						  			</div>

						  		<label class="col-md-2 control-label" for="apacthdr_auditno">Audit No</label>  
						  			<div class="col-md-3"> <!--- value="<?php// echo "auditno";?>" -->
						  			<input id="apacthdr_auditno" name="apacthdr_auditno" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						  		</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="apacthdr_suppcode">Creditor</label>	 
								 <div class="col-md-3">
									  <div class='input-group'>
										<input id="apacthdr_suppcode" name="apacthdr_suppcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

						  		<label class="col-md-2 control-label" for="apacthdr_recdate">Post Date</label>  
						  			<div class="col-md-3">
									<input id="apacthdr_recdate" name="apacthdr_recdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>">
						  		</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="apacthdr_payto">Pay To</label>	  
								<div class="col-md-3">
									  <div class='input-group'>
										<input id="apacthdr_payto" name="apacthdr_payto" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>

						  		<label class="col-md-2 control-label" for="apacthdr_actdate">Doc Date</label>  
						  			<div class="col-md-3">
									<input id="apacthdr_actdate" name="apacthdr_actdate" type="date" maxlength="12" class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>" max="<?php echo date("Y-m-d"); ?>">
						  		</div>
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label" for="apacthdr_document">Document No</label>  
						  			<div class="col-md-3">
										<input id="apacthdr_document" name="apacthdr_document" type="text" maxlength="30" class="form-control input-sm">
						  			</div>

						  		<label class="col-md-2 control-label" for="apacthdr_category">Category</label>	  
						  		<div class="col-md-3">
									  <div class='input-group'>
										<input id="apacthdr_category" name="apacthdr_category" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>
							</div>

							<div class="form-group">
							<label class="col-md-2 control-label" for="apacthdr_deptcode">Department</label>
							<div class="col-md-3">
									  <div class='input-group'>
										<input id="apacthdr_deptcode" name="apacthdr_deptcode" type="text" maxlength="12" class="form-control input-sm" data-validation="required">
										<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
									  </div>
									  <span class="help-block"></span>
								  </div>
							</div>

							<div class="form-group">
								
					    	<label class="col-md-2 control-label" for="apacthdr_remarks">Remarks</label> 
					    		<div class="col-md-8"> 
					    		<textarea class="form-control input-sm" name="apacthdr_remarks" rows="2" cols="55" maxlength="400" id="apacthdr_remarks" ></textarea>
					    		</div>
					    	
					   			 </div>


							<div class="form-group">
						  		
							 <label class="col-md-2 control-label" for="apacthdr_amount">Invoice Amount</label>  
							  	<div class="col-md-3">
										<input id="apacthdr_amount" name="apacthdr_amount" maxlength="12" class="form-control input-sm">  <!--data-validation-allowing="float" -->
				 				</div>

							 <label class="col-md-2 control-label" for="apacthdr_outamount">Total Detail Amount</label>  
							  	<div class="col-md-3">
										<input id="apacthdr_outamount" name="apacthdr_outamount" maxlength="12" class="form-control input-sm" rdonly>  <!--data-validation-allowing="float" -->
				 				</div>
							</div>

							 <!-- <button type="button" id='cancel' class='btn btn-info btn-sm pull-right' style='margin: 0.2%'>Cancel</button> -->
							<button type="button" id='save' class='btn btn-info btn-sm pull-right' style='margin: 0.2%;display: none;'>Save</button>



							
					</form>
				</div>
			</div>
			
			<div class='panel panel-info' id="ap_parent">
				<div class="panel-heading">Detail</div>
					<div class="panel-body">
						<form id='formdata2' class='form-horizontal' style='width:99%'>
					    	<div id="jqGrid2_c" class='col-md-12' style="padding:0 0 15px 0">
					            <table id="jqGrid2" class="table table-striped"></table>
					            <div id="jqGridPager2"></div>
					        </div>
						</form>
					</div>
				</div>
		</div>
		
		

	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level-->
	<script src="invoiceAP.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<!--<script src="../../../../assets/js/dialogHandler.js"></script>-->

<script>
		
</script>
</body>
</html>