<?php 
	include_once('../../../../header.php'); 
?>

<style>
	#detail {
		
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    transform-origin: 0 50%;
    transform: rotate(-90deg);
    white-space: nowrap;
    display: block;
    position: absolute;
    bottom: 0;
    left: 2%;
}

</style>

<body>


		
	<!-------------------------------- Search + table ---------------------->
	<div class='row'>
		<form id="searchForm" class="formclass" style='width:99%'>
			<fieldset>
				<div class="ScolClass">
						<div name='Scol'>Search By : </div>
				</div>
				<div class="StextClass">
					<input name="Stext" type="text" placeholder="Search here ..." class="form-control text-uppercase">
				</div>
			 </fieldset> 
		</form>
	

		<div class='row'>
				<div>
					 
            <button type="button" id='cancelBut' class='btn btn-info btn-sm pull-right' style='margin: 0.2%'>Cancel</button>
		<button type="button" id='postedBut' class='btn btn-info btn-sm pull-right' style='margin: 0.2%'>Post</button>
					
				</div>
		</div>

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
			<form class='form-horizontal' style='width:100%' id='formdata'>
				<div class='col-md-12'>
					<div class='panel panel-info'>
						<div id="detail" class="panel-heading" style="padding: 10px 90px"><b>CREDIT</b></div>
							<div class="panel-body">

								<div class="prevnext btn-group pull-right"></div>
								<input id="source" name="source" type="hidden">
								<input id="trantype" name="trantype" type="hidden">

				

									<div class="form-group">
									  <label class="col-md-2 control-label" for="auditno">Audit No</label>  
									  <div class="col-md-3">
									  <input id="auditno" name="auditno" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit hideOne>
									  </div>

									
									  <label class="col-md-2 control-label" for="pvno">Payment No</label>  
									  <div class="col-md-3">
									  <input id="pvno" name="pvno" type="text" maxlength="40" class="form-control input-sm" rdonly>
									  </div>
					                </div>
					                
									<div class="form-group">
										<label class="col-md-2 control-label" for="actdate">Payment Date</label>  
										<div class="col-md-3">
										  <div class='input-group'>
											<input id="actdate" name="actdate" type="date"  maxlength="30"  class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>"> 
										  </div>
										</div>
										
										<label class="col-md-2 control-label" for="paymode">Payment Mode</label>  
										<div class="col-md-3">
										  <div class='input-group'>
											<input id="paymode" name="paymode" type="text" maxlength="30" class="form-control input-sm" data-validation="required"/>
										  	<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										  </div>
										   <span class="help-block"></span>
										</div>
									</div>
					                
									<div class="form-group">
										<label class="col-md-2 control-label" for="bankcode">Bank Code</label>  
										<div class="col-md-3" id="bankcode_parent">
										  <div class='input-group'>
											<input id="bankcode" name="bankcode" type="text" maxlength="30" class="form-control input-sm" data-validation="required"/>
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
										  <div class='input-group'>
											<input id="cheqdate" name="cheqdate" type="date" maxlength="30"  class="form-control input-sm" data-validation="required" value="<?php echo date("Y-m-d"); ?>">
										  </div>
										 </div>

										  <label class="col-md-2 control-label" for="amount">Amount</label>  
											<div class="col-md-3">
												<input id="amount" name="amount" type="text" maxlength="30" class="form-control input-sm" data-validation="required"/>
											</div>

										
									</div>
								</div>
							</div>
						</div>	


				<div class='col-md-12'>
					<div class='panel panel-info'>
						<div id="detail" class="panel-heading"  style="padding: 10px 65px"><b>DEBIT</b></div>
							<div class="panel-body">


								<div class="form-group">
									<label class="col-md-2 control-label" for="payto">Bank Code</label>  
										<div class="col-md-3">
										  <div class='input-group'>
											<input id="payto" name="payto" type="text" maxlength="30" class="form-control input-sm" data-validation="required"/>
											<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
										  </div>
										  <span class="help-block"></span>
										</div>		
								</div>

								<div class="form-group">
										<label class="col-md-2 control-label" for="remarks">Remarks</label>  
											<div class="col-md-8">
												<textarea class="form-control input-sm" name="remarks" rows="1" cols="55" maxlength="100" id="remarks"></textarea>
											</div>
								</div>	

							</div>
						</div>
					</div>
				</div>

			</form>
		</div>
		
	<?php 
		include_once('../../../../footer.php'); 
	?>
	
	<!-- JS Implementing Plugins -->

	<!-- JS Customization -->

	<!-- JS Page Level -->
	<script src="bankTransfer.js"></script>
	<script src="../../../../assets/js/utility.js"></script>


</body>
</html>