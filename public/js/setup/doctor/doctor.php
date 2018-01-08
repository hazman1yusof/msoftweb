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
    left: 3%;
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
					<input name="Stext" type="search" placeholder="Search here ..." class="form-control text-uppercase">
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
			<form class='form-horizontal' style='width:99%' id='formdata'>
			<div class='col-md-12'>
					<div class='panel panel-info'>
						<div id="detail" class="panel-heading" style="padding: 10px 139px"><b>PERSONAL DETAILS</b></div>
							<div class="panel-body">
			
				<div class="prevnext btn-group pull-right"></div>
			 	

				<div class="form-group">
				  	<label class="col-md-2 control-label" for="doctorcode">Doctor Code</label>  
						  <div class="col-md-4">
						  <input id="doctorcode" name="doctorcode" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit>
						  </div>
               
			
				  	<label class="col-md-2 control-label" for="doctype">Doctor Type</label>  
						<div class="col-md-4">
						  <div class='input-group'>
							<input id="doctype" name="doctype" type="text" class="form-control input-sm" data-validation="required"/>
							<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
						</div>
						<span class="help-block"></span>
						</div> 
				</div>


				<div class="form-group">
					
				  <label class="col-md-2 control-label" for="doctorname">Doctor Name</label>  
				  <div class="col-md-10">
				  <input id="doctorname" name="doctorname" type="text" maxlength="200" class="form-control input-sm" data-validation="required">
				  </div>
                </div>

                <div class="form-group">
					<label class="col-md-2 control-label" for="department">Costcenter</label>  
					<div class="col-md-4">
					  <div class='input-group'>
						<input id="department" name="department" type="text" class="form-control input-sm" data-validation="required"/>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
				
					<label class="col-md-2 control-label" for="specialitycode">Speciality</label>  
					<div class="col-md-4">
					  <div class='input-group'>
						<input id="specialitycode" name="specialitycode" type="text" class="form-control input-sm" data-validation="required"/>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
				 </div>
				
				<div class="form-group">
					<label class="col-md-2 control-label" for="disciplinecode">Discipline</label>  
					<div class="col-md-4">
					  <div class='input-group'>
						<input id="disciplinecode" name="disciplinecode" type="text" class="form-control input-sm" data-validation="required"/>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
			
					<label class="col-md-2 control-label" for="creditorcode">Creditor</label>  
					<div class="col-md-4">
					  <div class='input-group'>
						<input id="creditorcode" name="creditorcode" type="text" class="form-control input-sm" data-validation="required"/>
						<a class='input-group-addon btn btn-primary'><span class='fa fa-ellipsis-h'></span></a>
					  </div>
					  <span class="help-block"></span>
					</div>
				</div>

				<div class="form-group">
				  <label class="col-md-2 control-label" for="classcode">Class</label>  
				  	<div class="col-md-4">
				    <table>
                             	<tr>
                             
                                <td><label class="radio-inline"><input type="radio" name="classcode" data-validation="required" value='CO' checked="">Consultant</label></td>
                                <td><label class="radio-inline"><input type="radio" name="classcode" data-validation="required" value='MO'>Medical Officer</label></td>
                               
								</tr>
							
				 			<tr>
                                <td><label class="radio-inline"><input type="radio" name="classcode" data-validation="required" value='PH'>Pharmacist</label></td>
                                <td><label class="radio-inline"><input type="radio" name="classcode" data-validation="required" value='PHY'>Physiotherapist</label></td>
                                
							</tr>
                     
                               </table>				
                	</div>
			
				  <label class="col-md-2 control-label" for="resigndate">Resign Date</label>  
				  <div class="col-md-4">
				  <input id="resigndate" name="resigndate" type="date" maxlength="30" class="form-control input-sm">
				  </div>
                </div>

                <div class="form-group">
				  <label class="col-md-2 control-label" for="admright">Admission Right</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="admright" value='1' data-validation="required" checked="">Yes</label>
					<label class="radio-inline"><input type="radio" name="admright" value='0' data-validation="">No</label>
				  </div>
				 
				  <label class="col-md-2 control-label" for="appointment">Appointment</label>  
				  <div class="col-md-4">
					<label class="radio-inline"><input type="radio" name="appointment" value='1' data-validation="required" checked="">Yes</label>
					<label class="radio-inline"><input type="radio" name="appointment" value='0' data-validation="">No</label>
				 </div>
				</div>


				<div class="form-group">
				 	<label class="col-md-2 control-label" for="intervaltime">Interval Time</label>  
				  		<div class="col-md-4">
				  			<div class="input-group">
				  			<input id="intervaltime" name="intervaltime" type="text" maxlength="30" class="form-control input-sm" data-validation="required">
				  			<span class="input-group-addon">minutes</span>
				  			</div>
				  		</div>

				  	 <label class="col-md-2 control-label" for="recstatus">Record Status</label>  
				  <div class="col-md-4">
					<input id="recstatus" name="recstatus" type="text" class="form-control input-sm" frozeOnEdit hideOne>
				  </div>
				</div>
               
               


</div>
</div>
</div>



				<div class='col-md-12'>
					<div class='panel panel-info'>
						<div id="detail" class="panel-heading" style="padding: 10px 130px"><b>CONTACT ADDRESS</b></div>
							<div class="panel-body">

				<div class="form-group">
				  <label class="col-md-2 control-label" for="company">Company</label>  
				  <div class="col-md-8">
				  <input id="company" name="company" type="text" maxlength="100" class="form-control input-sm" >
				  </div>
                </div>

                <div class="form-group">
				  <label class="col-md-2 control-label" for="address1">Address</label>  
				  <div class="col-md-8">
				  <input id="address1" name="address1" type="text" class="form-control input-sm" >
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address2" name="address2" type="text" class="form-control input-sm">
				  </div>
				</div>
				
				<div class="form-group">
				  <div class="col-md-offset-2 col-md-8">
				  <input id="address3" name="address3" type="text" class="form-control input-sm">
				  </div>
				</div>
                
                <div class="form-group">
				  <label class="col-md-2 control-label" for="postcode">Postcode</label>  
				  <div class="col-md-3">
				  <input id="postcode" name="postcode" type="text" class="form-control input-sm" >
				 </div>
				
				  <label class="col-md-2 control-label" for="statecode">State</label>  
				  <div class="col-md-3">
				  <input id="statecode" name="statecode" type="text" class="form-control input-sm">
				 </div>
				 </div>

				 <div class="form-group">
				  <label class="col-md-2 control-label" for="countrycode">Country</label>  
				  <div class="col-md-3">
				  <input id="countrycode" name="countrycode" type="text" class="form-control input-sm" >
				 </div>
				 
				  <label class="col-md-2 control-label" for="gstno">GST No</label>  
				  <div class="col-md-3">
				  <input id="gstno" name="gstno" type="text" class="form-control input-sm" >
				 </div>
				 </div>

				  <div class="form-group">
				  <label class="col-md-2 control-label" for="res_tel">Home</label>  
				  <div class="col-md-3">
				  <input id="res_tel" name="res_tel" type="text" class="form-control input-sm">
				 </div>
				
				  <label class="col-md-2 control-label" for="tel_hp">H/Phone</label>  
				  <div class="col-md-3">
				  <input id="tel_hp" name="tel_hp" type="text" class="form-control input-sm">
				 </div>
				 </div>

				  <div class="form-group">
				  <label class="col-md-2 control-label" for="off_tel">Office</label>  
				  <div class="col-md-3">
				  <input id="off_tel" name="off_tel" type="text" class="form-control input-sm">
				 </div>
				
				  <label class="col-md-2 control-label" for="operationtheatre">Operation Theatre (OT)</label>  
				  <div class="col-md-3">
					<label class="radio-inline"><input type="radio" name="operationtheatre" value='1'  checked="">Yes</label>
					<label class="radio-inline"><input type="radio" name="operationtheatre" value='0' >No</label>
				 </div>
				 </div>

</div>
</div>
</div>
	
					<div class="form-group">
					<label class="col-md-2 control-label" for="adduser">Created By</label>  
						<div class="col-md-3">
						  	<input id="adduser" name="adduser" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="upduser">Last Entered</label>  
						  	<div class="col-md-3">
								<input id="upduser" name="upduser" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label" for="adddate">Created Date</label>  
						<div class="col-md-3">
						  	<input id="adddate" name="adddate" type="text" class="form-control input-sm" frozeOnEdit hideOne>
						</div>

						<label class="col-md-2 control-label" for="upddate">Last Entered Date</label>  
						  	<div class="col-md-3">
								<input id="upddate" name="upddate" type="text" maxlength="30" class="form-control input-sm" frozeOnEdit hideOne>
						  	</div>
				</div>  

				<div class="form-group">
					<label class="col-md-2 control-label" for="lastcomputerid">Computer Id</label>  
						<div class="col-md-3">
						  	<input id="lastcomputerid" name="lastcomputerid" type="text" class="form-control input-sm" data-validation="required" frozeOnEdit rdonly>
						</div>

						<label class="col-md-2 control-label" for="lastipaddress">IP Address</label>  
						  	<div class="col-md-3">
								<input id="lastipaddress" name="lastipaddress" type="text" maxlength="30" class="form-control input-sm" data-validation="required" frozeOnEdit rdonly>
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
	<script src="doctorScript.js"></script>
	<script src="../../../../assets/js/utility.js"></script>
	<script src="../../../../assets/js/dialogHandler.js"></script>

<script>
		
</script>
</body>
</html>